<?php

/* ------------------- Staff accounts ------------------- */

namespace App\Http\Controllers\Manage\System;

use App\Http\Controllers\Manage\ManageController;
use App\Models\CallCenter\AgentStatusLog;
use App\Models\CallCenter\Call;
use App\Models\CallCenter\CsatRating;
use App\Models\User;
use App\Models\Users\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SystemUsersController extends ManageController
{
    public function index(Request $request)
    {
        return view('manage.system.users.index', [
            'users' => User::staff()
                ->with('roles:id,name,slug')
                ->when($request->filled('q'), function ($q) use ($request) {
                    $term = '%' . $request->input('q') . '%';
                    $q->where(fn ($w) => $w->where('first_name', 'like', $term)
                        ->orWhere('last_name', 'like', $term)
                        ->orWhere('email', 'like', $term));
                })
                ->latest('id')
                ->paginate(25)
                ->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('manage.system.users.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name'  => ['nullable', 'string', 'max:80'],
            'email'      => ['required', 'email', 'max:190', 'unique:su_users,email'],
            'phone'      => ['nullable', 'string', 'max:20', 'unique:su_users,phone'],
            'password'   => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'roles'      => ['required', 'array', 'min:1'],
            'roles.*'    => ['integer', 'exists:su_roles,id'],
        ]);

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'] ?? null,
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
            'password'   => Hash::make($data['password']),
            'status'     => 'active',
        ]);

        $user->roles()->sync($data['roles']);

        $this->logAction('created', 'staff', $user, 'Added ' . $user->name);

        return redirect()->route('manage.system-users.index')->with('success', 'Staff account created.');
    }

    public function show(int $id)
    {
        $user = User::staff()->with('roles.permissions')->findOrFail($id);

        $isAgent = $user->roles->pluck('slug')->intersect(['agent', 'supervisor', 'super_admin'])->isNotEmpty();

        return view('manage.system.users.show', [
            'user'    => $user,
            'isAgent' => $isAgent,
            'metrics' => $isAgent ? $this->agentMetrics($user) : null,
        ]);
    }

    /* Everything the agent detail page shows beyond the account record
       itself, computed live from su_calls rather than the daily rollup
       table, which has nothing in it until that job exists */
    private function agentMetrics(User $user): array
    {
        $calls = Call::where('agent_id', $user->id);

        $current      = AgentStatusLog::currentStatusFor($user);
        $activeCall   = Call::inProgress()->where('agent_id', $user->id)->latest('started_at')->first();

        $totalCalls    = (clone $calls)->count();
        $resolvedCalls = (clone $calls)->where('resolution_status', 'resolved')->count();

        $days = collect(range(6, 0))->map(fn ($i) => today()->subDays($i));
        $weekChart = [
            'labels' => $days->map(fn ($d) => $d->format('D'))->all(),
            'counts' => $days->map(fn ($d) => (clone $calls)->whereDate('started_at', $d)->count())->all(),
        ];

        return [
            'current_status'    => $activeCall ? 'on_call' : $current['status'],
            'status_since'      => $current['since'],
            'active_call'       => $activeCall,
            'total_calls'       => $totalCalls,
            'avg_handle_time'   => (int) round((clone $calls)->whereNotNull('duration')->avg('duration') ?? 0),
            'resolution_rate'   => $totalCalls > 0 ? round(($resolvedCalls / $totalCalls) * 100, 1) : null,
            'csat_avg'          => CsatRating::where('agent_id', $user->id)->avg('rating'),
            'last_call_handled' => (clone $calls)->whereNotNull('ended_at')->latest('ended_at')->first()?->ended_at,
            'week_chart'        => $weekChart,
        ];
    }

    public function edit(int $id)
    {
        return view('manage.system.users.edit', $this->formData() + [
            'user' => User::staff()->with('roles')->findOrFail($id),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $user = User::staff()->findOrFail($id);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name'  => ['nullable', 'string', 'max:80'],
            'email'      => ['required', 'email', 'max:190', 'unique:su_users,email,' . $id],
            'phone'      => ['nullable', 'string', 'max:20', 'unique:su_users,phone,' . $id],
            'password'   => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()],
            'roles'      => ['required', 'array', 'min:1'],
        ]);

        /* An administrator must not be able to strip their own access */
        if ($user->id === Auth::id() && ! in_array((string) $user->roles->first()?->id, array_map('strval', $data['roles']), true)) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user->update(collect($data)->except(['password', 'roles'])->toArray());

        if (! empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        $user->roles()->sync($data['roles']);

        $this->logAction('updated', 'staff', $user);

        return back()->with('success', 'Staff account saved.');
    }

    public function toggle(Request $request, int $id)
    {
        $user = User::staff()->findOrFail($id);

        if ($user->id === Auth::id()) {
            return $this->respond($request, false, 'You cannot suspend your own account.');
        }

        $user->update(['status' => $user->status === 'active' ? 'suspended' : 'active']);

        return $this->respond($request, true, 'Account is now ' . $user->status . '.');
    }

    /* An admin correcting a stuck or wrong presence state, for example an
       agent who closed their browser without going offline. Setting
       someone offline also resolves any call still attributed to them,
       since that call is almost certainly stale by the time an admin is
       stepping in to fix their status by hand. */
    public function setPresence(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => ['required', 'in:online,offline,break'],
        ]);

        $user = User::staff()->findOrFail($id);

        AgentStatusLog::create([
            'user_id'    => $user->id,
            'status'     => $data['status'],
            'changed_at' => now(),
        ]);

        if ($data['status'] === 'offline') {
            Call::inProgress()->where('agent_id', $user->id)->get()->each(function (Call $call) {
                $call->update([
                    'status'   => $call->hasBeenAnswered() ? 'answered' : 'missed',
                    'ended_at' => now(),
                    'duration' => $call->started_at ? abs(now()->getTimestamp() - $call->started_at->getTimestamp()) : null,
                ]);
                $call->events()->create(['event_type' => 'ended', 'event_time' => now()]);
            });
        }

        return $this->respond($request, true, $user->name . ' is now marked ' . $data['status'] . '.');
    }

    public function destroy(int $id)
    {
        $user = User::staff()->findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->isSuperAdmin() && User::staff()->whereHas('roles', fn ($q) => $q->where('slug', 'super_admin'))->count() <= 1) {
            return back()->with('error', 'This is the only super administrator. Promote someone else first.');
        }

        $user->delete();

        return redirect()->route('manage.system-users.index')->with('success', 'Staff account removed.');
    }

    private function formData(): array
    {
        return [
            'roles' => UserRole::orderBy('name')->get(),
        ];
    }
}

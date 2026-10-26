{{-- --------------- --}}
{{-- Add / edit supplier dialog. Vars: $open (Alpine flag), $title, $url, $method, $supplier (null for add) --}}
{{-- --------------- --}}
@php
    $initial = [
        'first_name'    => $supplier->first_name ?? '',
        'last_name'     => $supplier->last_name ?? '',
        'phone'         => $supplier->phone ?? '',
        'address'       => $supplier->address ?? '',
        'other_details' => $supplier->other_details ?? '',
    ];
@endphp
<div class="sw-md-overlay" x-cloak x-show="{{ $open }}" x-transition.opacity
     @click.self="{{ $open }}=false" @keydown.escape.window="{{ $open }}=false"
     data-url="{{ $url }}" data-method="{{ $method }}"
     data-next="{{ route('manage.workspace.suppliers', ['page' => 'index']) }}"
     x-data="{
        initial: @js($initial),
        f: @js($initial),
        error: '',
        busy: false,
        async submit() {
            if (this.busy) return;
            this.error = '';
            const body = {};
            for (const k in this.f) { body[k] = String(this.f[k]).trim(); }
            if (!body.first_name) { this.error = 'First name is required.'; return; }
            if (!body.phone) { this.error = 'Phone is required.'; return; }
            this.busy = true;
            try {
                const response = await fetch(this.$root.dataset.url, {
                    method: this.$root.dataset.method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '',
                    },
                    body: JSON.stringify(body),
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(Object.values(data.errors || {}).flat().join(' ') || data.message || 'Unable to save the supplier.');
                }
                const next = this.$root.dataset.next;
                if (this.$root.dataset.method === 'PUT' || window.location.pathname === new URL(next).pathname) { window.location.reload(); } else { window.location.href = next; }
            } catch (e) {
                this.error = e.message;
                this.busy = false;
            }
        }
     }"
     x-effect="if ({{ $open }}) { error=''; busy=false; $nextTick(() => $refs.first && $refs.first.focus()); } else { f = { ...initial }; }">
    <form class="sw-md-dialog sw-sup" role="dialog" aria-modal="true" @submit.prevent="submit()" @click.stop novalidate>
        <h3>{{ $title }}</h3>
        <div class="sw-sup-field">
            <input x-ref="first" x-model="f.first_name" type="text" maxlength="100" placeholder=" " autocomplete="off" required>
            <label>First name</label>
        </div>
        <div class="sw-sup-field">
            <input x-model="f.last_name" type="text" maxlength="255" placeholder=" " autocomplete="off">
            <label>Last name (optional)</label>
        </div>
        <div class="sw-sup-field">
            <input x-model="f.phone" type="tel" inputmode="tel" maxlength="255" placeholder=" " autocomplete="off" required>
            <label>Phone</label>
        </div>
        <div class="sw-sup-field">
            <input x-model="f.address" type="text" placeholder=" " autocomplete="off">
            <label>Address (optional)</label>
        </div>
        <div class="sw-sup-field sw-sup-field--multi">
            <textarea x-model="f.other_details" rows="3" placeholder=" "></textarea>
            <label>Other details (optional)</label>
        </div>
        <p class="sw-md-error" x-show="error" x-text="error" role="alert"></p>
        <div class="sw-md-actions">
            <button type="button" class="sw-md-cancel" @click="{{ $open }}=false">Cancel</button>
            <button type="submit" class="sw-md-save" :disabled="busy">Save</button>
        </div>
    </form>
</div>
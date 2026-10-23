<?php
namespace App\Http\Controllers\Manage\Cashbox;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\Cashbox\OpenShiftRequest;
use App\Http\Requests\Manage\Cashbox\CloseShiftRequest;
use App\Models\Cashbox\Shift;
use App\Services\Cashbox\ShiftService;
use App\Services\Pos\PosAuthorization;
use Illuminate\Http\JsonResponse;
class ShiftController extends Controller {
 public function index(PosAuthorization $auth): JsonResponse { $auth->require('pos.shifts.view'); return response()->json(Shift::query()->latest('id')->paginate(20)); }
 public function show(Shift $record,PosAuthorization $auth): JsonResponse { $auth->require('pos.shifts.view');return response()->json($record); }
 public function store(OpenShiftRequest $request,ShiftService $service,PosAuthorization $auth): JsonResponse {
  $auth->require('pos.shifts.open'); return response()->json($service->open((int)$request->user()->id,$request->validated()),201);
 }
 public function close(CloseShiftRequest $request,Shift $record,ShiftService $service,PosAuthorization $auth): JsonResponse {
  $auth->require('pos.shifts.close');
  $staff = \Illuminate\Support\Facades\DB::table('su_staff')->where('user_id',$request->user()->id)->value('id');
  abort_unless($staff && ((int)$record->staff_id === (int)$staff || $request->user()->hasPermission('pos.shifts.review')),403);
  return response()->json($service->close($record,$request->validated()));
 }
}

<?php
namespace App\Http\Controllers\Manage\Inventory;
use App\Http\Controllers\Controller;
use App\Models\Inventory\UnitOfMeasure;
use App\Http\Requests\Manage\Inventory\UnitOfMeasureRequest;
use App\Services\Pos\PosAuthorization;
use App\Services\Inventory\UnitOfMeasureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class UnitOfMeasureController extends Controller {
    public function index(Request $request, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.inventory.view');
        return response()->json(UnitOfMeasure::query()->orderByDesc('id')->paginate(min(100, max(1, (int)$request->input('per_page', 20)))));
    }
    public function show(UnitOfMeasure $record, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.inventory.view'); return response()->json($record);
    }
    public function store(UnitOfMeasureRequest $request, UnitOfMeasureService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.inventory.create');
        return response()->json($service->create($request->validated()), 201);
    }
    public function update(UnitOfMeasureRequest $request, UnitOfMeasure $record, UnitOfMeasureService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.inventory.update');
        return response()->json($service->update($record, $request->validated()));
    }
    public function destroy(UnitOfMeasure $record, UnitOfMeasureService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.inventory.delete'); $service->delete($record);
        return response()->json(['deleted' => true]);
    }
}

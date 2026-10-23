<?php
namespace App\Http\Controllers\Manage\Suppliers;
use App\Http\Controllers\Controller;
use App\Models\Suppliers\Supplier;
use App\Http\Requests\Manage\Suppliers\SupplierRequest;
use App\Services\Pos\PosAuthorization;
use App\Services\Suppliers\SupplierService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class SupplierController extends Controller {
    public function index(Request $request, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.suppliers.view');
        return response()->json(Supplier::query()->orderByDesc('id')->paginate(min(100, max(1, (int)$request->input('per_page', 20)))));
    }
    public function show(Supplier $record, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.suppliers.view'); return response()->json($record);
    }
    public function store(SupplierRequest $request, SupplierService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.suppliers.manage');
        return response()->json($service->create($request->validated()), 201);
    }
    public function update(SupplierRequest $request, Supplier $record, SupplierService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.suppliers.manage');
        return response()->json($service->update($record, $request->validated()));
    }
    public function destroy(Supplier $record, SupplierService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.suppliers.manage'); $service->delete($record);
        return response()->json(['deleted' => true]);
    }
}

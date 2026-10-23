<?php
namespace App\Http\Controllers\Manage\Inventory;
use App\Http\Controllers\Controller;
use App\Models\Inventory\Brand;
use App\Http\Requests\Manage\Inventory\BrandRequest;
use App\Services\Pos\PosAuthorization;
use App\Services\Inventory\BrandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class BrandController extends Controller {
    public function index(Request $request, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.brands.view');
        return response()->json(Brand::query()->orderByDesc('id')->paginate(min(100, max(1, (int)$request->input('per_page', 20)))));
    }
    public function show(Brand $record, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.brands.view'); return response()->json($record);
    }
    public function store(BrandRequest $request, BrandService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.brands.manage');
        return response()->json($service->create($request->validated()), 201);
    }
    public function update(BrandRequest $request, Brand $record, BrandService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.brands.manage');
        return response()->json($service->update($record, $request->validated()));
    }
    public function destroy(Brand $record, BrandService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.brands.manage'); $service->delete($record);
        return response()->json(['deleted' => true]);
    }
}

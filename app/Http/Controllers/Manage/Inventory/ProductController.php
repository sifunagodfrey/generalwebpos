<?php
namespace App\Http\Controllers\Manage\Inventory;
use App\Http\Controllers\Controller;
use App\Models\Inventory\Product;
use App\Http\Requests\Manage\Inventory\ProductRequest;
use App\Services\Pos\PosAuthorization;
use App\Services\Inventory\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class ProductController extends Controller {
    public function index(Request $request, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.products.view');
        return response()->json(Product::query()->orderByDesc('id')->paginate(min(100, max(1, (int)$request->input('per_page', 20)))));
    }
    public function show(Product $record, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.products.view'); return response()->json($record);
    }
    public function store(ProductRequest $request, ProductService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.products.create');
        return response()->json($service->create($request->validated()), 201);
    }
    public function update(ProductRequest $request, Product $record, ProductService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.products.update');
        return response()->json($service->update($record, $request->validated()));
    }
    public function destroy(Product $record, ProductService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.products.delete'); $service->delete($record);
        return response()->json(['deleted' => true]);
    }
}

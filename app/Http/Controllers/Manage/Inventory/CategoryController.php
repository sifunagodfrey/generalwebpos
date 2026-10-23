<?php
namespace App\Http\Controllers\Manage\Inventory;
use App\Http\Controllers\Controller;
use App\Models\Inventory\Category;
use App\Http\Requests\Manage\Inventory\CategoryRequest;
use App\Services\Pos\PosAuthorization;
use App\Services\Inventory\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class CategoryController extends Controller {
    public function index(Request $request, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.categories.view');
        return response()->json(Category::query()->orderByDesc('id')->paginate(min(100, max(1, (int)$request->input('per_page', 20)))));
    }
    public function show(Category $record, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.categories.view'); return response()->json($record);
    }
    public function store(CategoryRequest $request, CategoryService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.categories.manage');
        return response()->json($service->create($request->validated()), 201);
    }
    public function update(CategoryRequest $request, Category $record, CategoryService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.categories.manage');
        return response()->json($service->update($record, $request->validated()));
    }
    public function destroy(Category $record, CategoryService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.categories.manage'); $service->delete($record);
        return response()->json(['deleted' => true]);
    }
}

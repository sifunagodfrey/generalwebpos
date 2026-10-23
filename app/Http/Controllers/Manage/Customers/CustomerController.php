<?php
namespace App\Http\Controllers\Manage\Customers;
use App\Http\Controllers\Controller;
use App\Models\Customers\Customer;
use App\Http\Requests\Manage\Customers\CustomerRequest;
use App\Services\Pos\PosAuthorization;
use App\Services\Customers\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class CustomerController extends Controller {
    public function index(Request $request, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.customers.view');
        return response()->json(Customer::query()->orderByDesc('id')->paginate(min(100, max(1, (int)$request->input('per_page', 20)))));
    }
    public function show(Customer $record, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.customers.view'); return response()->json($record);
    }
    public function store(CustomerRequest $request, CustomerService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.customers.manage');
        return response()->json($service->create($request->validated()), 201);
    }
    public function update(CustomerRequest $request, Customer $record, CustomerService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.customers.manage');
        return response()->json($service->update($record, $request->validated()));
    }
    public function destroy(Customer $record, CustomerService $service, PosAuthorization $auth): JsonResponse {
        $auth->require('pos.customers.manage'); $service->delete($record);
        return response()->json(['deleted' => true]);
    }
}

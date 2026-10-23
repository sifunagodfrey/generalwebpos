<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
class PosPolicyServiceProvider extends ServiceProvider {
 public function boot(): void {
  Gate::policy(\App\Models\Inventory\Product::class,\App\Policies\Pos\ProductPolicy::class);
  Gate::policy(\App\Models\Inventory\Category::class,\App\Policies\Pos\CategoryPolicy::class);
  Gate::policy(\App\Models\Inventory\Brand::class,\App\Policies\Pos\BrandPolicy::class);
  Gate::policy(\App\Models\Inventory\UnitOfMeasure::class,\App\Policies\Pos\UnitOfMeasurePolicy::class);
  Gate::policy(\App\Models\Customers\Customer::class,\App\Policies\Pos\CustomerPolicy::class);
  Gate::policy(\App\Models\Suppliers\Supplier::class,\App\Policies\Pos\SupplierPolicy::class);
 }
}

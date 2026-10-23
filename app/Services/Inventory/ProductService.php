<?php
namespace App\Services\Inventory;
use App\Models\Inventory\Product;
use Illuminate\Support\Facades\DB;
class ProductService {
    public function create(array $data): Product {
        return DB::transaction(fn () => Product::query()->create($data));
    }
    public function update(Product $record, array $data): Product {
        return DB::transaction(function () use ($record, $data) {
            $record->fill($data)->save(); return $record->refresh();
        });
    }
    public function delete(Product $record): void {
        DB::transaction(fn () => $record->delete());
    }
}

<?php
namespace App\Services\Inventory;
use App\Models\Inventory\Category;
use Illuminate\Support\Facades\DB;
class CategoryService {
    public function create(array $data): Category {
        return DB::transaction(fn () => Category::query()->create($data));
    }
    public function update(Category $record, array $data): Category {
        return DB::transaction(function () use ($record, $data) {
            $record->fill($data)->save(); return $record->refresh();
        });
    }
    public function delete(Category $record): void {
        DB::transaction(fn () => $record->delete());
    }
}

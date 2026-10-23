<?php
namespace App\Services\Inventory;
use App\Models\Inventory\Brand;
use Illuminate\Support\Facades\DB;
class BrandService {
    public function create(array $data): Brand {
        return DB::transaction(fn () => Brand::query()->create($data));
    }
    public function update(Brand $record, array $data): Brand {
        return DB::transaction(function () use ($record, $data) {
            $record->fill($data)->save(); return $record->refresh();
        });
    }
    public function delete(Brand $record): void {
        DB::transaction(fn () => $record->delete());
    }
}

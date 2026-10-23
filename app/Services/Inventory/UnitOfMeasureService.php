<?php
namespace App\Services\Inventory;
use App\Models\Inventory\UnitOfMeasure;
use Illuminate\Support\Facades\DB;
class UnitOfMeasureService {
    public function create(array $data): UnitOfMeasure {
        return DB::transaction(fn () => UnitOfMeasure::query()->create($data));
    }
    public function update(UnitOfMeasure $record, array $data): UnitOfMeasure {
        return DB::transaction(function () use ($record, $data) {
            $record->fill($data)->save(); return $record->refresh();
        });
    }
    public function delete(UnitOfMeasure $record): void {
        DB::transaction(fn () => $record->delete());
    }
}

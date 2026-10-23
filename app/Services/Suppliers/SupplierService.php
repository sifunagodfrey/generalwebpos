<?php
namespace App\Services\Suppliers;
use App\Models\Suppliers\Supplier;
use Illuminate\Support\Facades\DB;
class SupplierService {
    public function create(array $data): Supplier {
        return DB::transaction(fn () => Supplier::query()->create($data));
    }
    public function update(Supplier $record, array $data): Supplier {
        return DB::transaction(function () use ($record, $data) {
            $record->fill($data)->save(); return $record->refresh();
        });
    }
    public function delete(Supplier $record): void {
        DB::transaction(fn () => $record->delete());
    }
}

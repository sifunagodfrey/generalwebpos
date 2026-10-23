<?php
namespace App\Services\Customers;
use App\Models\Customers\Customer;
use Illuminate\Support\Facades\DB;
class CustomerService {
    public function create(array $data): Customer {
        return DB::transaction(fn () => Customer::query()->create($data));
    }
    public function update(Customer $record, array $data): Customer {
        return DB::transaction(function () use ($record, $data) {
            $record->fill($data)->save(); return $record->refresh();
        });
    }
    public function delete(Customer $record): void {
        DB::transaction(fn () => $record->delete());
    }
}

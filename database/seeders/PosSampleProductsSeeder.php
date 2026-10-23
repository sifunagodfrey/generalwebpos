<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class PosSampleProductsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['su_categories', 'su_brands', 'su_units_of_measure', 'su_products', 'su_barcode_labels', 'su_stock_batches'] as $table) {
            if (!Schema::hasTable($table)) {
                throw new RuntimeException("Missing {$table}. Run the POS schema migrations first.");
            }
        }

        // Demo values only, denominated in KES. Not real market-price quotations.
        // All demo barcodes use a reserved internal DEMO- prefix.
        $items = [
            ['Food Staples', 'Generic', 'Kg', 'Maize Flour 2 kg', 140, 190, 45],
            ['Food Staples', 'Generic', 'Kg', 'Wheat Flour 2 kg', 145, 200, 35],
            ['Food Staples', 'Generic', 'Kg', 'White Rice 1 kg', 170, 230, 60],
            ['Food Staples', 'Generic', 'Kg', 'Sugar 1 kg', 125, 175, 55],
            ['Food Staples', 'Generic', 'Kg', 'Dry Beans 1 kg', 150, 210, 32],
            ['Cooking Essentials', 'Generic', 'Litre', 'Cooking Oil 1 L', 240, 315, 28],
            ['Cooking Essentials', 'Generic', 'Kg', 'Table Salt 1 kg', 35, 55, 75],
            ['Cooking Essentials', 'Generic', 'Piece', 'Tea Leaves 250 g', 135, 185, 38],
            ['Dairy & Bakery', 'Generic', 'Litre', 'UHT Milk 500 ml', 48, 70, 50],
            ['Dairy & Bakery', 'Generic', 'Piece', 'White Bread 400 g', 55, 75, 25],
            ['Dairy & Bakery', 'Generic', 'Piece', 'Eggs Tray 30 pcs', 390, 480, 15],
            ['Beverages', 'Generic', 'Piece', 'Bottled Water 500 ml', 20, 35, 90],
            ['Beverages', 'Generic', 'Piece', 'Orange Soda 500 ml', 45, 65, 55],
            ['Beverages', 'Generic', 'Piece', 'Mango Juice 1 L', 150, 210, 23],
            ['Household', 'Generic', 'Piece', 'Laundry Bar Soap 800 g', 95, 135, 42],
            ['Household', 'Generic', 'Piece', 'Dishwashing Liquid 500 ml', 100, 150, 38],
            ['Household', 'Generic', 'Piece', 'Toilet Tissue 4 Pack', 125, 175, 35],
            ['Personal Care', 'Generic', 'Piece', 'Toothpaste 100 ml', 100, 150, 34],
            ['Personal Care', 'Generic', 'Piece', 'Bath Soap 175 g', 65, 95, 48],
            ['Personal Care', 'Generic', 'Piece', 'Shampoo 250 ml', 170, 245, 20],
        ];

        DB::transaction(function () use ($items): void {
            $categoryIds = [];
            $brandIds = [];
            $unitIds = [];
            foreach ($items as $index => [$category, $brand, $unit, $name, $cost, $price, $quantity]) {
                if (!isset($categoryIds[$category])) {
                    $categoryIds[$category] = DB::table('su_categories')->where('name', $category)
                        ->whereNull('business_id')->whereNull('branch_id')->value('id');
                    if (!$categoryIds[$category]) {
                        $categoryIds[$category] = DB::table('su_categories')->insertGetId([
                            'name' => $category, 'description' => 'Sample category for demo products.',
                            'created_at' => now(), 'business_id' => null, 'branch_id' => null,
                        ]);
                    }
                }
                if (!isset($brandIds[$brand])) {
                    $brandIds[$brand] = DB::table('su_brands')->where('name', $brand)
                        ->whereNull('business_id')->whereNull('branch_id')->value('id');
                    if (!$brandIds[$brand]) {
                        $brandIds[$brand] = DB::table('su_brands')->insertGetId([
                            'name' => $brand, 'created_at' => now(), 'business_id' => null, 'branch_id' => null,
                        ]);
                    }
                }
                if (!isset($unitIds[$unit])) {
                    $unitIds[$unit] = DB::table('su_units_of_measure')->where('name', $unit)
                        ->whereNull('business_id')->whereNull('branch_id')->value('id');
                    if (!$unitIds[$unit]) {
                        $unitIds[$unit] = DB::table('su_units_of_measure')->insertGetId([
                            'name' => $unit, 'allows_decimal' => $unit !== 'Piece',
                            'business_id' => null, 'branch_id' => null,
                            'created_at' => now(), 'updated_at' => now(),
                        ]);
                    }
                }
                $barcode = sprintf('DEMO-GEN-%04d', $index + 1);
                $label = DB::table('su_barcode_labels')->where('code', $barcode)->first();
                // Never claim ownership of or overwrite an existing label belonging to another product.
                if ($label && $label->product_id !== null) {
                    continue;
                }
                $productId = DB::table('su_products')->where('name', $name)
                    ->whereNull('business_id')->whereNull('branch_id')->value('id');
                $created = false;
                if (!$productId) {
                    $productId = DB::table('su_products')->insertGetId([
                        'name' => $name, 'category_id' => $categoryIds[$category],
                        'brand_id' => $brandIds[$brand], 'unit_of_measure_id' => $unitIds[$unit],
                        'cost_price' => $cost, 'selling_price' => $price,
                        'tax_type' => 'standard_rated', 'description' => 'Demo item; edit before real trading.',
                        'has_variants' => false, 'is_active' => true,
                        'business_id' => null, 'branch_id' => null,
                        'created_at' => now(), 'updated_at' => now(),
                    ]);
                    $created = true;
                }
                if (!$label) {
                    DB::table('su_barcode_labels')->insert([
                        'code' => $barcode, 'product_id' => $productId,
                        'is_assigned' => true, 'created_at' => now(),
                        'business_id' => null, 'branch_id' => null,
                    ]);
                }
                // Only place demo opening stock for a newly created product.
                if ($created) {
                    DB::table('su_stock_batches')->insert([
                        'product_id' => $productId, 'quantity' => $quantity,
                        'cost_price' => $cost, 'received_at' => now(),
                        'business_id' => null, 'branch_id' => null,
                        'created_at' => now(), 'updated_at' => now(),
                    ]);
                }
            }
        });
    }
}

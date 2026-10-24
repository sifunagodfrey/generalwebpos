@extends('manage.layouts.workspace')
@section('title', $product ? 'Edit product' : 'Add product')
@section('heading', $product ? 'Edit product' : 'Add product')

@push('styles')
    <link rel="stylesheet"
          href="{{ asset('css/suweka-inventory.css') }}?v={{ filemtime(public_path('css/suweka-inventory.css')) }}">
@endpush
@section('content')
@php
    // Safe accessor: $product is a stdClass (or null on create)
    $field = function (string $key, $default = null) use ($product) {
        return old($key, $product !== null ? ($product->{$key} ?? $default) : $default);
    };
    $action = $product
        ? route('manage.inventory.product.update', $product->id)
        : route('manage.inventory.product.store');
@endphp

<form class="swif-page" method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if ($product) @method('PUT') @endif

    {{-- No visible status control in this layout; preserve the current value --}}
    <input type="hidden" name="is_active" value="{{ $field('is_active', 1) }}">

    @if ($errors->any())
        <div class="swi-error">{{ $errors->first() }}</div>
    @endif

    <div class="swif-grid">

        {{-- ============ Left column ============ --}}
        <div class="swif-col">

            <section class="swif-card">
                <h2 class="swif-card-title">Basic details</h2>

                <div class="swif-media-row">
                    <span class="swif-media-thumb"><i class="fa-solid fa-box"></i></span>
                    <label class="swif-photo-btn">
                        <i class="fa-regular fa-image"></i> Add photo
                        <input type="file" name="photo" accept="image/*" hidden>
                    </label>
                </div>

                <label class="swif-field">
                    <span class="swif-field-label">Product name</span>
                    <input name="name" required maxlength="150" autocomplete="off"
                           value="{{ $field('name') }}">
                </label>

                <div class="swif-field-line">
                    <label class="swif-field">
                        <span class="swif-field-label">Category (optional)</span>
                        <select name="category_id">
                            <option value="">None</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    @selected((string) $field('category_id') === (string) $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <a class="swif-field-add" target="_blank" rel="noopener" title="Manage categories"
                       href="{{ route('manage.workspace.inventory', ['page' => 'categories']) }}">
                        <i class="fa-solid fa-circle-plus"></i>
                    </a>
                </div>

                <div class="swif-field-line">
                    <label class="swif-field">
                        <span class="swif-field-label">Brand (optional)</span>
                        <select name="brand_id">
                            <option value="">None</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    @selected((string) $field('brand_id') === (string) $brand->id)>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <a class="swif-field-add" target="_blank" rel="noopener" title="Manage brands"
                       href="{{ route('manage.workspace.inventory', ['page' => 'brands']) }}">
                        <i class="fa-solid fa-circle-plus"></i>
                    </a>
                </div>

                <div class="swif-field-line" style="margin-bottom:0">
                    <label class="swif-field">
                        <span class="swif-field-label">Unit of measure (optional)</span>
                        <select name="unit_of_measure_id">
                            <option value="">None</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}"
                                    @selected((string) $field('unit_of_measure_id') === (string) $unit->id)>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <a class="swif-field-add" target="_blank" rel="noopener" title="Units of measure"
                       href="{{ route('manage.workspace.inventory', ['page' => 'products']) }}">
                        <i class="fa-solid fa-circle-plus"></i>
                    </a>
                </div>
            </section>

            <section class="swif-card">
                <h2 class="swif-card-title">Description</h2>
                <label class="swif-field swif-field--last">
                    <span class="swif-field-label">Description (optional)</span>
                    <textarea name="description" rows="6" maxlength="4000">{{ $field('description') }}</textarea>
                </label>
            </section>

        </div>

        {{-- ============ Right column ============ --}}
        <div class="swif-col">

            <section class="swif-card">
                <h2 class="swif-card-title">Pricing &amp; tax</h2>

                <div class="swif-row-2">
                    <label class="swif-field">
                        <span class="swif-field-label">Cost price</span>
                        <input type="number" name="cost_price" step="0.01" min="0" required
                               value="{{ $field('cost_price', 0) }}">
                    </label>
                    <label class="swif-field">
                        <span class="swif-field-label">Selling price</span>
                        <input type="number" name="selling_price" step="0.01" min="0" required
                               value="{{ $field('selling_price', 0) }}">
                    </label>
                </div>

                <label class="swif-field">
                    <span class="swif-field-label">Discount price (optional)</span>
                    <input type="number" name="discount_price" step="0.01" min="0"
                           value="{{ $field('discount_price') }}">
                </label>

                <label class="swif-field swif-field--last">
                    <span class="swif-field-label">Tax type</span>
                    <select name="tax_type">
                        @foreach (['standard_rated' => 'Standard rated', 'zero_rated' => 'Zero rated', 'exempt' => 'Exempt'] as $key => $label)
                            <option value="{{ $key }}" @selected($field('tax_type', 'standard_rated') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </section>

            <section class="swif-card">
                <h2 class="swif-card-title">Identification</h2>
                <p class="swif-help" style="margin:0 0 12px">Barcode to scan or type at checkout</p>

                <div class="swif-barcode">
                    <i class="fa-solid fa-barcode"></i>
                    <input name="barcode" maxlength="100" autocomplete="off"
                           placeholder="Scan or type a barcode (optional)"
                           value="{{ $field('barcode') }}">
                    <button type="button" class="swif-camera" title="Scan with camera" aria-label="Scan with camera">
                        <i class="fa-solid fa-camera"></i>
                    </button>
                </div>
            </section>

            <section class="swif-card">
                <h2 class="swif-card-title">Variants &amp; stock</h2>

                <label class="swif-field">
                    <span class="swif-field-label">Low-stock alert threshold</span>
                    <input type="number" name="low_stock_threshold" min="0" step="1"
                           value="{{ $field('low_stock_threshold') }}">
                    <span class="swif-help">Blank uses the Alerts default. Applies to each variant too.</span>
                </label>

                <div class="swif-toggle-row">
                    <div>
                        <strong>Expiry alerts</strong>
                        <span>Warn before a stock batch expires</span>
                    </div>
                    <label class="swif-switch">
                        <input type="checkbox" name="expiry_alerts" value="1" @checked($field('expiry_alerts'))>
                        <span class="swif-switch-slider"></span>
                    </label>
                </div>

                <div class="swif-toggle-row">
                    <div>
                        <strong>Has variants</strong>
                        <span>e.g. sizes or flavors, each with its own price and stock.</span>
                    </div>
                    <label class="swif-switch">
                        <input type="checkbox" name="has_variants" value="1" @checked($field('has_variants'))>
                        <span class="swif-switch-slider"></span>
                    </label>
                </div>

                @unless ($product)
                    <label class="swif-field swif-field--last">
                        <span class="swif-field-label">Starting stock</span>
                        <input type="number" name="starting_stock" min="0" step="any"
                               value="{{ $field('starting_stock', 0) }}">
                    </label>
                @endunless
            </section>

        </div>
    </div>

    <div class="swif-actions">
        <a class="swif-cancel"
           href="{{ $product ? route('manage.inventory.product.show', $product->id) : route('manage.workspace.inventory', ['page' => 'products']) }}">
            Cancel
        </a>
        <button class="swif-save" type="submit">{{ $product ? 'Save changes' : 'Save' }}</button>
    </div>
</form>

@endsection
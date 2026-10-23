{{-- SUWEKA-SALES-BLADE-FIX-V2 --}}
@extends('manage.layouts.workspace')
@section('title','New sale')
@section('heading','New sale')
@section('subtitle','')
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-sales.css') }}">
<div class="sw-sales" x-data="saleCheckout({
    products: {{ Illuminate\Support\Js::from($products->items()) }},
    searchUrl: {{ Illuminate\Support\Js::from(route('manage.workspace.sale')) }},
    paymentUrl: {{ Illuminate\Support\Js::from(route('manage.sales.payment')) }},
    submitUrl: {{ Illuminate\Support\Js::from(route('manage.sales.checkout')) }},
    quoteUrl: {{ Illuminate\Support\Js::from(route('manage.sales.quote')) }},
    barcodeUrl: {{ Illuminate\Support\Js::from(route('manage.sales.barcode')) }},
    recallUrl: {{ Illuminate\Support\Js::from(route('manage.sales.recall', 0)) }},
    shiftOpen: {{ Illuminate\Support\Js::from((bool) $shift) }},
    discountAllowed: {{ Illuminate\Support\Js::from(auth()->user()->hasAnyPermission(['pos.sales.discount','pos.sales.manage','pos.discounts.manage','pos.discounts.create','pos.sales.create'])) }},
    query: {{ Illuminate\Support\Js::from($search) }}
})" x-init="initCheckout()" @sw:open-cart.window="tab='cart'; window.scrollTo({top:0})">
    <div class="sw-sale-cols" :class="{'sw-view-cart':tab==='cart'}">
        <section class="sw-sale-catalog">
            <form class="sw-search-card sw-sale-search" action="{{ route('manage.workspace.sale') }}" method="GET" @submit.prevent="submitSearch()">
                <div class="sw-search">
                    <i class="fa-solid fa-qrcode"></i>
                    <input name="q" x-model="query" x-ref="scanner" autocomplete="off" placeholder="Search or scan products" aria-label="Search or scan products" @input.debounce.300ms="liveSearch()">
                    {{-- --------------- --}}
                    {{-- Camera button, mobile only --}}
                    {{-- --------------- --}}
                    <button type="button" class="sw-sale-camera" aria-label="Scan with camera"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 8h3l1.5-2h7L17 8h3v11H4z"/><circle cx="12" cy="13" r="3.5"/></svg></button>
                </div>
                {{-- --------------- --}}
                {{-- Open cart screen, mobile only --}}
                {{-- --------------- --}}
                <button type="button" class="sw-sale-cart-btn" @click="tab='cart'; window.scrollTo({top:0})" aria-label="Open cart"><span class="sw-nav-symbol"><i class="fa-solid fa-cart-shopping"></i><b class="sw-bottom-count" x-text="cartCount()" hidden>0</b></span></button>
            </form>

            <div id="sw-catalog-results" class="sw-catalog-results">
                <div class="sw-sale-grid">
                    @forelse($products as $product)
                        @php
                            $saleProductPayload = [
                                'id' => $product->id,
                                'variant_id' => $product->variant_id,
                                'name' => $product->name,
                                'price' => (float) $product->selling_price,
                                'stock' => (float) $product->stock,
                                'tax_type' => $product->tax_type,
                            ];
                        @endphp
                        <button
                            type="button"
                            class="sw-product-card"
                            data-sale-product="{{ json_encode($saleProductPayload, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT) }}"
                            @click="add(JSON.parse($el.dataset.saleProduct))"
                        >
                            <div class="sw-product-image">
                                <svg class="sw-product-placeholder-icon" viewBox="0 0 32 32" width="32" height="32" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.8">
                                    <rect x="4" y="4" width="8" height="8"></rect>
                                    <rect x="18" y="4" width="8" height="8"></rect>
                                    <rect x="4" y="18" width="8" height="8"></rect>
                                    <path d="M22 17v11M16.5 22.5h11"></path>
                                </svg>
                                <span class="sw-product-qty" x-show="quantityFor({{ $product->id }}, {{ $product->variant_id ?? 'null' }})>0" x-text="quantityFor({{ $product->id }}, {{ $product->variant_id ?? 'null' }})"></span>
                            </div>
                            <div class="sw-product-details">
                                <b title="{{ $product->name }}">{{ $product->name }}</b>
                                <small>{{ number_format($product->stock,0) }} in stock</small>
                                <strong>{{ number_format($product->selling_price,0) }}</strong>
                            </div>
                        </button>
                    @empty
                        <div class="sw-empty">{{ $search ? 'No products match your search.' : 'No products found.' }}</div>
                    @endforelse
                </div>
                <div class="sw-page-tools">
                    <span>Page {{ $products->currentPage() }}</span>
                    <span class="sw-pager">
                        @if($products->previousPageUrl())
                            <a href="{{ $products->previousPageUrl() }}" @click.prevent="navigatePage($event)" aria-label="Previous products"><i class="fa-solid fa-chevron-left"></i></a>
                        @endif
                        @if($products->nextPageUrl())
                            <a href="{{ $products->nextPageUrl() }}" @click.prevent="navigatePage($event)" aria-label="Next products"><i class="fa-solid fa-chevron-right"></i></a>
                        @endif
                    </span>
                </div>
            </div>
        </section>

        <aside class="sw-sale-cart" id="sw-active-cart">
            <h3>Current sale</h3>
            <template x-if="!lines.length"><p class="sw-cart-empty">Cart is empty. Tap a product to add it.</p></template>
            <div class="sw-sale-lines">
                <template x-for="(line,index) in lines" :key="line.product_id+'-'+(line.variant_id||0)">
                    <div class="sw-cart-card">
                        <div class="sw-cart-card-head">
                            <div class="sw-cart-icon"><i class="fa-solid fa-box-archive"></i></div>
                            <div class="sw-cart-meta">
                                <div class="sw-cart-row-title">
                                    <strong x-text="line.name"></strong>
                                </div>
                                <small x-text="money(line.price) + ' per unit'"></small>
                                <button type="button" class="sw-discount-link" @click="editDiscount(index)">Discount: <span x-text="money(appliedDiscount(line))"></span> per unit</button>
                            </div>
                            <div class="sw-cart-side">
                                <span class="sw-cart-price" x-text="money(lineTotal(line))"></span>
                                <button type="button" class="sw-discount-trigger" @click="editDiscount(index)" aria-label="Edit discount">%</button>
                            </div>
                        </div>
                        <div class="sw-cart-card-foot">
                            <div class="sw-qty-pill">
                                <button type="button" @click="change(index,-1)" aria-label="Decrease quantity">&minus;</button>
                                <button type="button" class="sw-qty-edit" @click="editQuantity(index)" :aria-label="'Set quantity for '+line.name" title="Enter quantity" x-text="line.quantity"></button>
                                <button type="button" @click="change(index,1)" aria-label="Increase quantity">+</button>
                            </div>
                            <button type="button" class="sw-remove-btn" @click="remove(index)" aria-label="Remove item"><i class="fa-regular fa-trash-can"></i></button>
                        </div>
                    </div>
                </template>
            </div>
            <div class="sw-sale-bottom">
                <div class="sw-subtotal"><span>Subtotal</span><span x-text="money(subtotal())"></span></div>
                <div class="sw-total"><strong>Total</strong><strong x-text="money(total())"></strong></div>
                <template x-if="error"><div class="sw-sale-error" role="alert" x-text="error"></div></template>
                <button type="button" class="sw-sale-primary" :disabled="!lines.length" @click="goPayment()"><i class="fa-solid fa-chevron-right text-white"></i><span>Charge <span x-text="money(total())"></span></span></button>
                <div class="sw-sale-actions">
                    <button type="button" @click="hold()" :disabled="!lines.length || busy"><i class="fa-solid fa-pause text-primary"></i><span>Hold sale</span></button>
                    <button type="button" class="danger" @click="clear()" :disabled="!lines.length || busy"><i class="fa-regular fa-trash-can text-[red]"></i><span>Clear</span></button>
                </div>
            </div>
        </aside>
    </div>

    <div class="sw-quantity-overlay" x-cloak x-show="quantityDialog.open" @keydown.escape.window="closeQuantity()" @click.self="closeQuantity()">
      <form class="sw-quantity-modal" role="dialog" aria-modal="true" aria-labelledby="sw-quantity-title" @submit.prevent="applyQuantity()" @click.stop>
        <h3 id="sw-quantity-title" x-text="'Quantity: ' + quantityDialog.name"></h3>
        <label for="sw-quantity-input">Quantity</label>
        <input id="sw-quantity-input" x-ref="quantityInput" type="number" inputmode="decimal" min="0" step="any" x-model="quantityDialog.value" autocomplete="off">
        <p class="sw-quantity-invalid" x-show="quantityDialog.error" x-text="quantityDialog.error" role="alert"></p>
        <div class="sw-quantity-actions"><button type="button" @click="closeQuantity()">Cancel</button><button type="submit" class="sw-quantity-set">Set</button></div>
      </form>
    </div>

    <div class="sw-discount-overlay" x-cloak x-show="discountDialog.open" x-transition.opacity @keydown.escape.window="closeDiscount()" @click.self="closeDiscount()">
      <section class="sw-discount-modal" role="dialog" aria-modal="true" aria-labelledby="sw-item-discount-title" @click.stop>
        <h3 id="sw-item-discount-title">Item discount</h3>
        <p class="sw-discount-item-name" x-text="discountDialog.name"></p>
        <p>Unit price: <strong x-text="money(discountDialog.price)"></strong></p>
        <p>Product / offer discount: <strong x-text="money(discountDialog.baseDiscount)"></strong> per unit</p>
        <p>Currently applied: <strong x-text="money(discountDialog.appliedDiscount)"></strong> per unit</p>
        <p class="sw-discount-note">A checkout discount replaces the product discount for this item. It does not stack.</p>
        <label for="sw-discount-type">Discount type</label>
        <select id="sw-discount-type" x-model="discountDialog.type" @change="discountDialog.value='0'; discountDialog.error=''">
          <option value="amount">Amount off per unit</option>
          <option value="percent">Percentage off</option>
        </select>
        <label for="sw-discount-value" x-text="discountDialog.type==='percent'?'Discount (%)':'Discount per unit'"></label>
        <input id="sw-discount-value" type="number" min="0" step="0.01" x-model="discountDialog.value" @input="discountDialog.error=''">
        <p class="sw-discount-invalid" x-show="discountDialog.error" x-text="discountDialog.error"></p>
        <div class="sw-discount-preview"><span>Item total</span><strong x-text="money(discountPreviewLine())"></strong></div>
        <div class="sw-discount-preview"><span>Cart total</span><strong x-text="money(discountPreviewCart())"></strong></div>
        <div class="sw-discount-actions">
          <button type="button" @click="closeDiscount()">Cancel</button>
          <button type="button" @click="resetDiscount()" :disabled="!discountAllowed">Use product discount</button>
          <button type="button" class="sw-discount-apply" @click="applyDiscount()" :disabled="!discountAllowed">Apply</button>
        </div>
      </section>
    </div>
</div>
<script src="{{ asset('js/suweka-sales.js') }}" defer></script>
@endsection

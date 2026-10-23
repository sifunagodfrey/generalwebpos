<div class="sw-search-card"><div class="sw-search"><i class="fa-solid fa-magnifying-glass"></i><input id="swProductSearch" oninput="swSearch(this,'sw-product')" placeholder="Search products" aria-label="Search products"></div></div>
<div class="sw-product-grid">
@forelse($records as $product)
<div class="sw-product-card sw-product" data-search="{{ strtolower(($product->name??'').' '.($product->sku??'')) }}"><div class="sw-product-image"><i class="fa-solid fa-qrcode"></i></div><div class="sw-product-details"><b>{{ $product->name??'Product' }}</b><small>{{ ($product->has_variants??false)?'Has variants':'Product' }}</small><strong>{{ number_format((float)($product->selling_price??$product->price??0),0) }}</strong></div></div>
@empty<div class="sw-empty">No products found.</div>@endforelse
</div><div class="sw-pagination">1–{{ $records->count() }} of {{ $records->count() }}</div>

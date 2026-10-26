@extends('manage.layouts.workspace')
@section('title', 'New purchase order')
@section('heading', 'New purchase order')

@section('content')
{{-- --------------- --}}
{{-- Pick a supplier, tap products to add them, set quantities and unit costs, place the order --}}
{{-- --------------- --}}
<div class="swo"
     data-url="{{ route('manage.suppliers.orders.store') }}"
     x-init="picking = !supplier"
     x-data="{
        supplier: @js($selected),
        suppliers: @js($suppliers),
        products: @js($products),
        q: '', sq: '', picking: false, tab: 'products',
        lines: [], busy: false, error: '',
        get shown() { const t = this.q.trim().toLowerCase(); return t ? this.products.filter(p => p.name.toLowerCase().includes(t)) : this.products; },
        get supplierList() { const t = this.sq.trim().toLowerCase(); return t ? this.suppliers.filter(s => (s.name + ' ' + (s.phone || '')).toLowerCase().includes(t)) : this.suppliers; },
        fmt(n) { return Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 2 }); },
        total() { return this.lines.reduce((sum, l) => sum + Number(l.qty || 0) * Number(l.cost || 0), 0); },
        add(p) {
            const line = this.lines.find(l => l.id === p.id);
            if (line) { line.qty = Number(line.qty) + 1; return; }
            this.lines.push({ id: p.id, name: p.name, qty: 1, cost: p.cost });
        },
        dec(i) {
            if (Number(this.lines[i].qty) <= 1) { this.lines.splice(i, 1); } else { this.lines[i].qty = Number(this.lines[i].qty) - 1; }
        },
        async place() {
            if (this.busy) return;
            this.error = '';
            if (!this.supplier) { this.error = 'Choose a supplier first.'; this.picking = true; return; }
            if (!this.lines.length) { this.error = 'Add at least one product.'; return; }
            this.busy = true;
            try {
                const response = await fetch(this.$root.dataset.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '',
                    },
                    body: JSON.stringify({
                        supplier_id: this.supplier.id,
                        lines: this.lines.map(l => ({ product_id: l.id, quantity: Number(l.qty), unit_cost: Number(l.cost || 0) })),
                    }),
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(Object.values(data.errors || {}).flat().join(' ') || data.message || 'Unable to place the order.');
                }
                window.location.href = data.redirect;
            } catch (e) {
                this.error = e.message;
                this.busy = false;
            }
        }
     }">

    <section class="sw-search-card swo-top">
        <button type="button" class="swo-supplier" @click="picking=true">
            <i class="fa-solid fa-truck"></i> <span x-text="supplier ? supplier.name : 'Choose supplier'"></span>
        </button>
        <div class="sw-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input x-model="q" placeholder="Search products to order" aria-label="Search products to order">
        </div>
    </section>

    <div class="swo-tabs">
        <button type="button" :class="{ active: tab==='products' }" @click="tab='products'">Products</button>
        <button type="button" :class="{ active: tab==='lines' }" @click="tab='lines'">Order (<span x-text="lines.length"></span>)</button>
    </div>

    <div class="swo-grid">
        <section class="swo-products" :class="{ 'swo-hide-m': tab !== 'products' }" aria-label="Products">
            <template x-for="p in shown" :key="p.id">
                <button type="button" class="swo-prod" @click="add(p)">
                    <i class="fa-solid fa-box-archive" aria-hidden="true"></i>
                    <span class="swo-prod-name"><b x-text="p.name"></b><small><span x-text="fmt(p.stock)"></span> in stock</small></span>
                    <span class="swo-prod-cost"><b x-text="fmt(p.cost)"></b><span class="swo-circ"><i class="fa-solid fa-plus"></i></span></span>
                </button>
            </template>
            <p class="swo-none" x-show="!shown.length">No products found.</p>
        </section>

        <aside class="swo-lines" :class="{ 'swo-hide-m': tab !== 'lines' }" aria-label="Order lines">
            <h3>Order lines</h3>
            <div class="swo-lines-list">
                <p class="swo-none" x-show="!lines.length">Tap a product to add it to the order.</p>
                <template x-for="(l, i) in lines" :key="l.id">
                    <div class="swo-line">
                        <div class="swo-line-top">
                            <strong x-text="l.name"></strong>
                            <button type="button" @click="lines.splice(i, 1)" aria-label="Remove line"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="swo-line-ctrl">
                            <button type="button" class="swo-circ" @click="dec(i)" aria-label="Less"><i class="fa-solid fa-minus"></i></button>
                            <span x-text="l.qty"></span>
                            <button type="button" class="swo-circ" @click="l.qty = Number(l.qty) + 1" aria-label="More"><i class="fa-solid fa-plus"></i></button>
                            <label class="swo-cost">@ <input type="number" min="0" step="any" inputmode="decimal" x-model="l.cost" aria-label="Unit cost"></label>
                        </div>
                    </div>
                </template>
            </div>
            <div class="swo-foot">
                <div class="swo-total"><span>Total cost</span><b x-text="fmt(total())"></b></div>
                <p class="sw-md-error" x-show="error" x-text="error" role="alert"></p>
                <button type="button" class="swo-place" :disabled="busy" @click="place()"><i class="fa-solid fa-check"></i> Place order</button>
            </div>
        </aside>
    </div>

    {{-- --------------- --}}
    {{-- Supplier chooser --}}
    {{-- --------------- --}}
    <div class="sw-md-overlay" x-cloak x-show="picking" x-transition.opacity @click.self="picking=false" @keydown.escape.window="picking=false"
         x-effect="if (picking) $nextTick(() => $refs.sinput && $refs.sinput.focus())">
        <div class="sw-md-dialog swo-pick" role="dialog" aria-modal="true" @click.stop>
            <h3>Choose supplier</h3>
            <div class="sw-search"><i class="fa-solid fa-magnifying-glass"></i><input x-ref="sinput" x-model="sq" placeholder="Search suppliers" aria-label="Search suppliers"></div>
            <ul class="swo-pick-list">
                <template x-for="s in supplierList" :key="s.id">
                    <li><button type="button" @click="supplier = s; picking = false"><b x-text="s.name"></b><small x-text="s.phone"></small></button></li>
                </template>
                <li class="swo-none" x-show="!supplierList.length">No suppliers found.</li>
            </ul>
            <div class="sw-md-actions"><button type="button" class="sw-md-cancel" @click="picking=false">Close</button></div>
        </div>
    </div>
</div>
@endsection
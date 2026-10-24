@extends('manage.layouts.workspace')
@section('title', 'Stock take')
@section('heading', 'Stock take')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/suweka-inventory.css') }}?v={{ filemtime(public_path('css/suweka-inventory.css')) }}">
@endpush

@section('content')
<div class="swi-wrap" x-data="stocktake()">
    <form method="GET" action="{{ route('manage.workspace.inventory', ['page' => 'stocktake']) }}" class="swi-search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input name="q" value="{{ $search }}" placeholder="Search products to count" autocomplete="off">
    </form>

    <div class="swi-grid">
        @foreach($products as $product)
            <button type="button" class="swi-card" @click="openModal({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->stock }})">
                <div class="swi-placeholder"><i class="fa-solid fa-box"></i></div>
                <div class="swi-card-body text-left">
                    <strong title="{{ $product->name }}">{{ $product->name }}</strong>
                    <small>{{ number_format($product->stock, 0) }} in stock</small>
                    <b>{{ number_format($product->selling_price, 0) }}</b>
                </div>
            </button>
        @endforeach
    </div>

    <div class="swi-pagination">
        <span>{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }}</span>
        <span>
            @if($products->previousPageUrl())<a href="{{ $products->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>@endif
            @if($products->nextPageUrl())<a href="{{ $products->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>@endif
            <a href="{{ route('manage.workspace.inventory', ['page' => 'stocktake']) }}"><i class="fa-solid fa-rotate-right"></i></a>
        </span>
    </div>

    {{-- Stocktake Modal --}}
    <div class="sw-md-overlay" x-cloak x-show="modal.open" x-transition.opacity @keydown.escape.window="closeModal()" @click.self="closeModal()">
        <form class="sw-stocktake-dialog" @submit.prevent="submitCount()" @click.stop>
            <button type="button" class="sw-stocktake-close" @click="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            
            <div class="sw-stocktake-header">
                <div class="sw-stocktake-icon"><i class="fa-solid fa-box"></i></div>
                <div>
                    <h3 x-text="modal.name"></h3>
                    <p>On record: <span x-text="modal.originalStock"></span></p>
                </div>
            </div>

            <div class="sw-stocktake-field">
                <label>Counted quantity</label>
                <input type="number" x-model.number="modal.count" min="0" step="any" required>
                {{-- Removed the range slider here --}}
            </div>

            <div class="sw-stocktake-field">
                <label>Note (optional)</label>
                <input type="text" x-model="modal.note" maxlength="1500" placeholder=" ">
            </div>

            <p class="sw-stocktake-error" x-show="modal.error" x-text="modal.error"></p>

            <button type="submit" class="sw-stocktake-submit" :disabled="modal.busy">
                <span x-show="!modal.busy">Record count</span>
                <span x-show="modal.busy">Saving...</span>
            </button>
        </form>
    </div>
</div>

<script>
function stocktake() {
    return {
        modal: {
            open: false,
            productId: null,
            name: '',
            originalStock: 0,
            count: 0,
            note: '',
            error: '',
            busy: false
        },
        openModal(id, name, stock) {
            this.modal = {
                open: true,
                productId: id,
                name: name,
                originalStock: stock,
                count: stock,
                note: '',
                error: '',
                busy: false
            };
        },
        closeModal() {
            this.modal.open = false;
        },
        async submitCount() {
            if (this.modal.busy) return;
            this.modal.busy = true;
            this.modal.error = '';

            try {
                const res = await fetch('{{ route('manage.inventory.stocktake.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({
                        product_id: this.modal.productId,
                        counted_quantity: this.modal.count,
                        note: this.modal.note
                    })
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed to save.');
                
                window.location.reload();
            } catch (e) {
                this.modal.error = e.message;
                this.modal.busy = false;
            }
        }
    }
}
</script>
@endsection
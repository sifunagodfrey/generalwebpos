@extends('manage.layouts.workspace')
@section('title','Returns')
@section('heading','Returns')
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-sales-submodules.css') }}">

<div x-data="returnsPage({ 
        detailUrl: '{{ route('manage.sales.returns.show', 'REPLACE_ID') }}',
        storeUrl: '{{ route('manage.sales.returns.store', 'REPLACE_ID') }}'
    })" 
    class="sws-returns" x-cloak>
    
    <!-- Left Panel: List of Sales -->
    <div class="sws-return-left" :class="{'sw-hidden-mobile': selectedSale}">
        <form method="GET" action="{{ route('manage.sales.returns') }}" class="sws-search-field">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="search" name="q" value="{{ $search }}" placeholder="Search by sale # or customer">
        </form>
        
        <div class="sws-return-list">
            @forelse($records as $sale)
                <button type="button" 
                        class="sws-list-row" 
                        :class="{'is-active': selectedSale?.id === {{ $sale->id }}}"
                        @click="loadSale({{ $sale->id }})">
                    <i class="fa-solid fa-receipt sws-purple"></i>
                    <span class="sws-list-copy">
                        <b>Sale #{{ $sale->id }}</b>
                        <small>{{ trim(($sale->first_name??'').' '.($sale->last_name??'')) ?: 'Walk-in customer' }}</small>
                    </span>
                    <strong>{{ number_format($sale->total,0) }}</strong>
                </button>
            @empty
                <div class="sws-empty">No completed sales found.</div>
            @endforelse
        </div>
        {{ $records->links() }}
    </div>

    <!-- Right Panel: Selected Sale Details -->
    <div class="sws-return-right" x-show="selectedSale" style="display: none;">
        <a href="#" @click.prevent="selectedSale = null" class="sws-link sws-mobile-back">
            <i class="fa-solid fa-arrow-left"></i> Back to sales
        </a>

        <template x-if="loadingSale">
            <div class="sws-empty">Loading sale details...</div>
        </template>

        <template x-if="!loadingSale && selectedSale">
            <div class="sws-return-details">
                <div class="sws-detail-card">
                    <h3>Invoice #<span x-text="selectedSale.id"></span></h3>
                    <p class="sws-muted" style="margin-bottom: 24px;">Total <span x-text="selectedSale.total"></span></p>
                    
                    <div class="sws-items">
                        <template x-for="line in saleLines" :key="line.id">
                            <div class="sws-item" style="border-bottom: 1px solid #eee5f8; padding: 20px 0;">
                                <div>
                                    <strong x-text="line.product_name" style="font-size: 16px; color: #2d384c;"></strong>
                                    <small style="margin-top: 4px; display: block; color: #697c9a;">
                                        Sold <span x-text="Number(line.quantity)"></span> at <span x-text="line.unit_price"></span>
                                        <template x-if="alreadyReturned[line.id] > 0">
                                            <span> | <span x-text="Number(alreadyReturned[line.id])"></span> already returned</span>
                                        </template>
                                    </small>
                                </div>
                                <div>
                                    <template x-if="(line.quantity - (alreadyReturned[line.id] || 0)) <= 0">
                                        <span class="sws-muted" style="font-size: 14px;">Fully returned</span>
                                    </template>
                                    <template x-if="(line.quantity - (alreadyReturned[line.id] || 0)) > 0">
                                        <button type="button" class="sws-btn-outline" @click="openQtyModal(line)">Credit note</button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="sws-detail-card" style="margin-top: 24px;">
                    <h3 style="margin-bottom: 24px; font-size: 16px; font-weight: 700; color: #2d384c;">Returns / credit history</h3>
                    
                    <template x-if="history.length === 0">
                        <p class="sws-muted" style="font-size: 14px; color: #697c9a;">No returns recorded yet.</p>
                    </template>
                    
                    <template x-for="record in history" :key="record.id">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 0;">
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <i class="fa-solid fa-box-open" style="color: #ea580c; font-size: 22px;"></i>
                                <span style="font-size: 15px; color: #2d384c;" x-text="Number(record.quantity) + ' unit(s) - ' + (record.reason || 'cause')"></span>
                            </div>
                            <span style="font-size: 14px; font-weight: 500; color: #2d384c;" x-text="'-' + Number(record.amount_refunded)"></span>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>

    <!-- Qty Selection Modal -->
    <div class="sws-modal-overlay" x-show="showQtyModal" @click.self="showQtyModal=false" style="display: none;">
        <div class="sws-modal" @click.stop>
            <h3>Credit note: <span x-text="currentItem?.product_name"></span></h3>
            <p>Sold <span x-text="Number(currentItem?.quantity)"></span>, <span x-text="Number((currentItem?.quantity - (alreadyReturned[currentItem?.id] || 0)))"></span> still available to return.</p>
            
            <label>Quantity returned</label>
            <input type="number" x-model.number="returnQty" min="1" :max="currentItem?.quantity - (alreadyReturned[currentItem?.id] || 0)">
            
            <label>Credit value</label>
            <input type="text" :value="(returnQty * currentItem?.unit_price).toFixed(2)" readonly>
            <p class="sws-help-text">Includes original discounts and tax.</p>

            <label>Reason <span style="color: #e34b4b;">*</span></label>
            <input type="text" x-model="returnReason" placeholder="Enter reason">
            <p class="sws-help-text" x-show="reasonError" style="color: #e34b4b; margin-top: -8px; margin-bottom: 16px;" x-text="reasonError"></p>

            <div class="sws-toggle-row">
                <div>
                    <strong>Return items to sellable stock</strong>
                    <span>Turn off for damaged or unusable goods.</span>
                </div>
                <label class="sws-toggle">
                    <input type="checkbox" x-model="restock">
                    <span class="slider"></span>
                </label>
            </div>

            <div class="sws-modal-actions">
                <button type="button" class="sws-btn-text" @click="showQtyModal=false">Cancel</button>
                <button type="button" class="sws-btn-primary" @click="reviewReturn()">Review credit note</button>
            </div>
        </div>
    </div>

    <!-- Confirm Modal -->
    <div class="sws-modal-overlay" x-show="showConfirmModal" @click.self="showConfirmModal=false" style="display: none;">
        <div class="sws-modal" @click.stop>
            <h3>Confirm credit note</h3>
            
            <p><span x-text="confirmData.item?.product_name"></span> × <span x-text="confirmData.qty"></span></p>
            <p>Total credit: KES <span x-text="confirmData.totalCredit?.toFixed(2)"></span></p>
            <p>Record cash refund: KES <span x-text="confirmData.totalCredit?.toFixed(2)"></span></p>
            
            <p x-show="confirmData.restock" style="margin-top:16px;">Returned goods will be restocked.</p>
            <p class="sws-help-text" style="margin-top: 12px;">Confirm only after arranging the listed refunds. This records refunds; it does not send money.</p>

            <div class="sws-modal-actions">
                <button type="button" class="sws-btn-text" @click="showConfirmModal=false" :disabled="busy">Cancel</button>
                <button type="button" class="sws-btn-primary" @click="confirmReturn()" :disabled="busy" x-text="busy ? 'Issuing...' : 'Confirm and issue'"></button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('returnsPage', (config) => ({
        detailUrl: config.detailUrl,
        storeUrl: config.storeUrl,
        selectedSale: null,
        saleLines: [],
        alreadyReturned: {},
        history: [],
        loadingSale: false,
        busy: false,
        
        showQtyModal: false,
        currentItem: null,
        returnQty: 1,
        returnReason: '',
        reasonError: '',
        restock: true,
        
        showConfirmModal: false,
        confirmData: {},

        async loadSale(id) {
            this.loadingSale = true;
            this.selectedSale = { id, total: 0 }; 
            try {
                const url = this.detailUrl.replace('REPLACE_ID', id);
                const res = await fetch(url, { 
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                if(res.ok) {
                    this.selectedSale = data.sale;
                    this.saleLines = data.lines;
                    this.alreadyReturned = data.alreadyReturned;
                    this.history = data.history || [];
                } else {
                    alert(data.message || 'Error loading sale');
                    this.selectedSale = null;
                }
            } catch (e) {
                console.error(e);
                this.selectedSale = null;
            } finally {
                this.loadingSale = false;
            }
        },

        openQtyModal(line) {
            this.currentItem = line;
            const remaining = line.quantity - (this.alreadyReturned[line.id] || 0);
            this.returnQty = remaining;
            this.returnReason = '';
            this.reasonError = '';
            this.restock = true;
            this.showQtyModal = true;
        },

        reviewReturn() {
            this.reasonError = '';
            if(!this.returnReason.trim()) {
                this.reasonError = 'A reason is required to process a return.';
                return;
            }
            const remaining = this.currentItem.quantity - (this.alreadyReturned[this.currentItem.id] || 0);
            if(this.returnQty < 1 || this.returnQty > remaining) {
                alert('Invalid quantity');
                return;
            }
            const totalCredit = this.returnQty * this.currentItem.unit_price;
            this.confirmData = {
                item: this.currentItem,
                qty: this.returnQty,
                totalCredit: totalCredit,
                reason: this.returnReason,
                restock: this.restock,
            };
            this.showQtyModal = false;
            this.showConfirmModal = true;
        },

        async confirmReturn() {
            if (this.busy) return;
            this.busy = true;
            
            try {
                const url = this.storeUrl.replace('REPLACE_ID', this.selectedSale.id);
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        sale_item_id: this.confirmData.item.id,
                        quantity: this.confirmData.qty,
                        reason: this.confirmData.reason,
                        restock: this.confirmData.restock ? 1 : 0
                    })
                });
                
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Error issuing credit note');
                
                this.showConfirmModal = false;
                // Refresh the details to show the new return in the history and update available quantities
                this.loadSale(this.selectedSale.id);
                
            } catch (e) {
                alert(e.message);
            } finally {
                this.busy = false;
            }
        }
    }));
});
</script>
@endsection
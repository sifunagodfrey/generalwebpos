@extends('manage.layouts.workspace')
@section('title', 'Issue credit note')
@section('heading', 'Issue credit note')

@section('content')
<div class="max-w-7xl mx-auto pb-20 md:pb-6 h-[calc(100vh-140px)] flex flex-col md:flex-row gap-0 border border-gray-200 rounded-2xl overflow-hidden bg-white shadow-sm" x-data="creditNoteApp()">
    
    <!-- Left Panel: Sales List -->
    <div class="w-full md:w-[400px] flex-shrink-0 border-r border-gray-200 flex flex-col">
        <div class="p-4 border-b border-gray-100">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="search" x-model="searchQuery" placeholder="Search by sale # or customer" class="w-full pl-11 pr-4 py-3 rounded-xl border-0 bg-[#f8f9fa] focus:ring-0 outline-none transition text-md">
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-[#f8f9fa]">
            @foreach($sales as $sale)
                <div @click="loadSale({{ $sale->id }})" 
                     class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between cursor-pointer hover:border-[#713FCE] transition group"
                     :class="{'border-[#713FCE] ring-1 ring-[#713FCE]': selectedSale && selectedSale.id === {{ $sale->id }}}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 flex justify-center text-[#713FCE]">
                            <i class="fa-solid fa-receipt text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">Sale #{{ $sale->id }}</p>
                            <p class="text-xs text-gray-500">{{ trim(($sale->first_name ?? '') . ' ' . ($sale->last_name ?? '')) ?: 'Walk-in customer' }}</p>
                        </div>
                    </div>
                    <p class="font-bold text-gray-900">{{ number_format($sale->total, 0) }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Right Panel: Sale Details -->
    <div class="hidden md:flex flex-1 bg-[#f4effb] flex-col relative">
        
        <!-- Default State -->
        <div x-show="!selectedSale" class="flex-1 flex flex-col items-center justify-center p-8">
            <div class="text-center">
                <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center mx-auto mb-4 text-[#713FCE]">
                    <i class="fa-solid fa-mouse-pointer text-2xl"></i>
                </div>
                <p class="text-gray-500 text-lg">Select a completed sale to process a return.</p>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-[#f4effb]/80 z-10" x-cloak>
            <i class="fa-solid fa-spinner fa-spin text-3xl text-[#713FCE]"></i>
        </div>

        <!-- Selected Sale Details -->
        <div x-show="selectedSale && !loading" class="flex-1 overflow-y-auto p-6" x-cloak>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Invoice #<span x-text="selectedSale?.id"></span></h3>
                
                <div class="space-y-4">
                    <template x-for="line in saleLines" :key="line.id">
                        <div class="border-b border-gray-100 pb-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-bold text-gray-900" x-text="line.product_name"></p>
                                    <p class="text-sm text-gray-500">Sold: <span x-text="Number(line.quantity)"></span> at <span x-text="line.unit_price"></span></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-700">Available: <span x-text="Number(line.quantity) - Number(alreadyReturned[line.id] || 0)"></span></p>
                                </div>
                            </div>

                            <!-- Return Form (Only show if there is available quantity) -->
                            <div x-show="(Number(line.quantity) - Number(alreadyReturned[line.id] || 0)) > 0" class="mt-3 flex flex-wrap items-center gap-3">
                                <input type="number" x-model="line.returnQty" min="1" :max="Number(line.quantity) - Number(alreadyReturned[line.id] || 0)" class="w-24 border-gray-300 rounded-lg focus:ring-[#713FCE] focus:border-[#713FCE] text-sm" placeholder="Qty">
                                <input type="text" x-model="line.returnReason" class="flex-1 border-gray-300 rounded-lg focus:ring-[#713FCE] focus:border-[#713FCE] text-sm" placeholder="Reason for return">
                                <label class="flex items-center gap-2 text-sm text-gray-700">
                                    <input type="checkbox" x-model="line.restock" class="rounded text-[#713FCE] focus:ring-[#713FCE]"> Restock
                                </label>
                                <button type="button" @click="submitReturn(line)" :disabled="line.processing" class="bg-[#713FCE] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#5a2ea6] transition disabled:opacity-50">
                                    <span x-show="!line.processing">Return</span>
                                    <i x-show="line.processing" class="fa-solid fa-spinner fa-spin"></i>
                                </button>
                            </div>
                            <div x-show="(Number(line.quantity) - Number(alreadyReturned[line.id] || 0)) <= 0" class="mt-2 text-sm text-green-600 font-medium">
                                Fully returned
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function creditNoteApp() {
    return {
        selectedSale: null,
        saleLines: [],
        alreadyReturned: {},
        loading: false,
        error: '',
        
        async loadSale(id) {
            this.loading = true;
            this.error = '';
            this.selectedSale = { id };
            try {
                // Use Laravel's route helper to inject the correct base path
                const url = "{{ route('manage.documents.api.sale', ':id') }}".replace(':id', id);
                
                const res = await fetch(url, { 
                    headers: { 
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!res.ok) {
                    if (res.status === 403) throw new Error('You do not have permission to process returns.');
                    if (res.status === 404) throw new Error('Sale not found.');
                    throw new Error('Failed to load sale details');
                }
                
                const data = await res.json();
                
                this.selectedSale = data.sale;
                this.saleLines = data.lines.map(l => ({ ...l, returnQty: 1, returnReason: '', restock: true, processing: false }));
                this.alreadyReturned = data.alreadyReturned;
            } catch (e) {
                alert(e.message);
                this.selectedSale = null;
            } finally {
                this.loading = false;
            }
        },
        
        async submitReturn(line) {
            if (!line.returnQty || line.returnQty <= 0) return alert('Enter a valid quantity');
            if (!line.returnReason.trim()) return alert('A reason is required');
            
            line.processing = true;
            try {
                // Use Laravel's route helper here as well
                const url = "{{ route('manage.documents.api.return', ':id') }}".replace(':id', this.selectedSale.id);
                
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({
                        sale_item_id: line.id,
                        quantity: line.returnQty,
                        reason: line.returnReason,
                        restock: line.restock ? 1 : 0
                    })
                });
                
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Error processing return');
                
                // Reload the sale to update the "Available" quantities
                await this.loadSale(this.selectedSale.id);
                alert('Credit note issued successfully!');
                
            } catch (e) {
                alert(e.message);
            } finally {
                line.processing = false;
            }
        }
    }
}
</script>
@endsection
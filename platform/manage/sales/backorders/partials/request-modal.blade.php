{{-- --------------- --}}
{{-- Add backorder request dialog, opened by showForm on the page wrapper --}}
{{-- --------------- --}}
<div class="sw-md-overlay" x-cloak x-show="showForm" x-transition.opacity
     @keydown.escape.window="showForm=false" @click.self="showForm=false"
     data-products-url="{{ route('manage.sales.backorders.products') }}"
     x-effect="if (showForm) $nextTick(() => $refs.product && $refs.product.focus())"
     x-init="if (customerId) { const c = customers.find(x => String(x.id) === String(customerId)); if (c) customerText = customerLabel(c); }"
     x-data="{
        productName: @js(old('product_name', '')),
        productId: @js(old('product_id', '')),
        matches: [],
        timer: null,
        customers: @js($customers),
        customerText: '',
        customerId: @js(old('customer_id', '')),
        customerOpen: false,
        customerList: [],
        error: '',
        searchProducts() {
            this.productId = '';
            clearTimeout(this.timer);
            const term = this.productName.trim();
            if (term.length < 2) { this.matches = []; return; }
            this.timer = setTimeout(async () => {
                try {
                    const response = await fetch(this.$root.dataset.productsUrl + '?q=' + encodeURIComponent(term), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                    const rows = response.ok ? await response.json() : [];
                    if (term === this.productName.trim()) this.matches = rows;
                } catch (e) { this.matches = []; }
            }, 250);
        },
        pickProduct(product) {
            this.productName = product.name;
            this.productId = product.id;
            this.matches = [];
        },
        customerLabel(c) {
            return [c.first_name, c.last_name].filter(Boolean).join(' ') + (c.phone ? ' - ' + c.phone : '');
        },
        chooseCustomer(c) {
            this.customerText = this.customerLabel(c);
            this.customerId = c.id;
            this.customerOpen = false;
        },
        guardSubmit(event) {
            if (this.customerText.trim() && !this.customerId) {
                event.preventDefault();
                this.error = 'Choose a customer from the list, or clear the field.';
                return;
            }
            this.error = '';
        }
     }">
    <form class="sw-md-dialog" method="POST" action="{{ route('manage.sales.missing.store') }}" role="dialog" aria-modal="true" aria-labelledby="sw-backorder-title" @click.stop @submit="guardSubmit($event)">
        @csrf
        <h3 id="sw-backorder-title">Add backorder request</h3>
        <p class="sw-md-note">Manual requests appear under Missing stock. Sale shortfalls are tracked under Backorders.</p>

        <div class="sw-md-field">
            <input type="text" name="product_name" required maxlength="250" autocomplete="off" x-ref="product" x-model="productName" @input="searchProducts()">
            <label>Product name</label>
        </div>
        <input type="hidden" name="product_id" :value="productId">
        <div class="sw-md-matches" x-show="matches.length" x-cloak>
            <span>Matches in catalog:</span>
            <template x-for="product in matches" :key="product.id">
                <button type="button" x-text="product.name" @click="pickProduct(product)"></button>
            </template>
        </div>

        <div class="sw-md-customer" @click.outside="customerOpen=false"
             x-effect="customerList = customers.filter(c => { const t = customerText.trim().toLowerCase(); return !t || customerLabel(c).toLowerCase().includes(t); })">
            <div class="sw-md-search">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="8" r="3.5"/><path d="M3 19c0-3.2 2.7-5.5 6-5.5 1 0 1.9.2 2.7.6"/><circle cx="16.5" cy="16.5" r="2.5"/><path d="M18.4 18.4 21 21"/></svg>
                <input type="text" placeholder="Customer name or phone" autocomplete="off" x-model="customerText" @focus="customerOpen=true" @input="customerId=''; customerOpen=true">
            </div>
            <input type="hidden" name="customer_id" :value="customerId">
            <ul class="sw-md-menu" x-show="customerOpen" x-cloak role="listbox">
                <template x-for="customer in customerList" :key="customer.id">
                    <li role="option" @mousedown.prevent @click="chooseCustomer(customer)">
                        <strong x-text="[customer.first_name, customer.last_name].filter(Boolean).join(' ')"></strong>
                        <small x-text="customer.phone"></small>
                    </li>
                </template>
                <li class="sw-md-empty" x-show="!customerList.length">No customers found</li>
            </ul>
        </div>

        <div class="sw-md-field">
            <input type="number" name="quantity" required min="0.01" step="any" inputmode="decimal" value="{{ old('quantity', 1) }}">
            <label>Requested quantity</label>
        </div>

        <div class="sw-md-field is-multiline">
            <textarea name="note" rows="2" maxlength="1500">{{ old('note') }}</textarea>
            <label>Note (optional)</label>
        </div>

        <p class="sw-md-error" x-show="error" x-text="error" role="alert"></p>
        @if($errors->any())<p class="sw-md-error">{{ $errors->first() }}</p>@endif

        <div class="sw-md-actions">
            <button type="button" class="sw-md-cancel" @click="showForm=false">Cancel</button>
            <button type="submit" class="sw-md-save">Save</button>
        </div>
    </form>
</div>
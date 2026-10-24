{{-- --------------- --}}
{{-- New discount dialog, opened by showForm on the page wrapper --}}
{{-- --------------- --}}
<div class="sw-md-overlay" x-cloak x-show="showForm" x-transition.opacity
     @keydown.escape.window="showForm=false" @click.self="showForm=false"
     data-products-url="{{ route('manage.sales.backorders.products') }}"
     x-data="{
        productLabel: @js(old('product_label', '')),
        productId: @js(old('product_id', '')),
        matches: [],
        timer: null,
        type: @js(filled(old('discount_amount')) ? 'amount' : 'percent'),
        value: @js(old('discount_percent', old('discount_amount', ''))),
        startDate: @js(substr(old('starts_at', now()->toDateString()), 0, 10)),
        endDate: @js(substr(old('ends_at', now()->addDays(7)->toDateString()), 0, 10)),
        error: '',
        searchProducts() {
            this.productId = '';
            clearTimeout(this.timer);
            const term = this.productLabel.trim();
            if (term.length < 2) { this.matches = []; return; }
            this.timer = setTimeout(async () => {
                try {
                    const response = await fetch(this.$root.dataset.productsUrl + '?q=' + encodeURIComponent(term), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                    const rows = response.ok ? await response.json() : [];
                    if (term === this.productLabel.trim()) this.matches = rows;
                } catch (e) { this.matches = []; }
            }, 250);
        },
        pickProduct(product) {
            this.productLabel = product.name;
            this.productId = product.id;
            this.matches = [];
        },
        guardSubmit(event) {
            this.error = '';
            if (!this.productId) { event.preventDefault(); this.error = 'Choose a product from the list.'; return; }
            if (this.value === '' || Number(this.value) <= 0) { event.preventDefault(); this.error = 'Enter a discount value.'; }
        }
     }">
    <form class="sw-md-dialog sw-dd" method="POST" action="{{ route('manage.sales.discounts.store') }}" role="dialog" aria-modal="true" aria-labelledby="sw-discount-title" @click.stop @submit="guardSubmit($event)">
        @csrf
        <h3 id="sw-discount-title">New discount</h3>

        {{-- --------------- --}}
        {{-- Product search --}}
        {{-- --------------- --}}
        <div class="sw-md-customer">
            <div class="sw-md-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="product_label" placeholder="Search product by name" autocomplete="off" x-model="productLabel" @input="searchProducts()">
            </div>
            <input type="hidden" name="product_id" :value="productId">
            <ul class="sw-md-menu" x-show="matches.length" x-cloak role="listbox">
                <template x-for="product in matches" :key="product.id">
                    <li role="option" @mousedown.prevent @click="pickProduct(product)"><strong x-text="product.name"></strong></li>
                </template>
            </ul>
        </div>

        {{-- --------------- --}}
        {{-- Percent or amount --}}
        {{-- --------------- --}}
        <div class="sw-dd-radios">
            <label><input type="radio" value="percent" x-model="type" @change="value=''"> Percent off</label>
            <label><input type="radio" value="amount" x-model="type" @change="value=''"> Amount off</label>
        </div>

        <div class="sw-dd-field">
            <input type="number" required min="0" step="any" inputmode="decimal" placeholder=" "
                   :name="type === 'percent' ? 'discount_percent' : 'discount_amount'"
                   :max="type === 'percent' ? 100 : null" x-model="value">
            <label x-text="type === 'percent' ? 'Percent (%)' : 'Amount'"></label>
        </div>

        {{-- --------------- --}}
        {{-- Dates, native picker behind the Change buttons --}}
        {{-- --------------- --}}
        <input type="hidden" name="starts_at" :value="startDate + ' 00:00:00'">
        <input type="hidden" name="ends_at" :value="endDate + ' 23:59:59'">

        <div class="sw-dd-dates">
            <div class="sw-dd-date">
                <span>Starts: <span x-text="startDate"></span></span>
                <button type="button" @click="$refs.startPicker.showPicker()">Change</button>
                <input type="date" x-ref="startPicker" x-model="startDate" tabindex="-1" aria-hidden="true">
            </div>
            <div class="sw-dd-date">
                <span>Ends: <span x-text="endDate"></span></span>
                <button type="button" @click="$refs.endPicker.showPicker()">Change</button>
                <input type="date" x-ref="endPicker" x-model="endDate" tabindex="-1" aria-hidden="true">
            </div>
        </div>

        <p class="sw-md-error" x-show="error" x-text="error" role="alert"></p>
        @if($errors->any())<p class="sw-md-error">{{ $errors->first() }}</p>@endif

        <div class="sw-md-actions">
            <button type="button" class="sw-md-cancel" @click="showForm=false">Cancel</button>
            <button type="submit" class="sw-md-save">Save</button>
        </div>
    </form>
</div>

<style>
/* --------------- */
/* New discount dialog (matches the Flutter reference) */
/* --------------- */
.sw-md-dialog.sw-dd{padding:28px;border-radius:28px;background:#ece6f0}
.sw-md-dialog.sw-dd h3{margin:0 0 20px;font-size:24px;font-weight:400;line-height:32px;color:#1d1b20}

/* Search box */
.sw-md-dialog.sw-dd .sw-md-customer{margin:0;width:100%}
.sw-md-dialog.sw-dd .sw-md-search{display:flex;align-items:center;height:48px;gap:16px;padding:0 14px;border:0;border-radius:10px;background:#f6f0fd}
.sw-md-dialog.sw-dd .sw-md-search i{width:20px;text-align:center;font-size:17px;color:#1d1b20}
.sw-md-dialog.sw-dd .sw-md-search input{flex:1;min-width:0;border:0;background:transparent;outline:0;font:inherit;font-size:16px;color:#1d1b20}

/* Radios */
.sw-md-dialog.sw-dd .sw-dd-radios{display:grid;grid-template-columns:1fr 1fr;margin:30px 0 14px}
.sw-md-dialog.sw-dd .sw-dd-radios label{display:flex;align-items:center;gap:14px;font-size:16px;color:#1d1b20;cursor:pointer}
.sw-md-dialog.sw-dd .sw-dd-radios input[type=radio]{-webkit-appearance:none;appearance:none;display:grid;place-content:center;flex:none;width:20px;height:20px;margin:10px;padding:0;border:2px solid #49454f;border-radius:50%;background:transparent;cursor:pointer}
.sw-md-dialog.sw-dd .sw-dd-radios input[type=radio]::before{content:"";width:10px;height:10px;border-radius:50%;background:#713fce;transform:scale(0);transition:transform .12s}
.sw-md-dialog.sw-dd .sw-dd-radios input[type=radio]:checked{border-color:#713fce}
.sw-md-dialog.sw-dd .sw-dd-radios input[type=radio]:checked::before{transform:scale(1)}

/* Value field: label sits inside, shrinks when focused or filled */
.sw-md-dialog.sw-dd .sw-dd-field{position:relative;height:52px;border-bottom:1px solid #79747e}
.sw-md-dialog.sw-dd .sw-dd-field input{display:block;width:100%;height:100%;padding:18px 0 0;border:0;border-radius:0;background:transparent;outline:0;font:inherit;font-size:16px;color:#1d1b20}
.sw-md-dialog.sw-dd .sw-dd-field label{position:absolute;left:0;top:50%;transform:translateY(-50%);font-size:16px;color:#49454f;pointer-events:none;transition:all .12s}
.sw-md-dialog.sw-dd .sw-dd-field input:focus + label,
.sw-md-dialog.sw-dd .sw-dd-field input:not(:placeholder-shown) + label{top:8px;transform:none;font-size:12px}
.sw-md-dialog.sw-dd .sw-dd-field:focus-within{border-bottom:2px solid #713fce}
.sw-md-dialog.sw-dd .sw-dd-field:focus-within label{color:#713fce}

/* Dates */
.sw-md-dialog.sw-dd .sw-dd-dates{margin-top:12px}
.sw-md-dialog.sw-dd .sw-dd-date{position:relative;display:flex;align-items:center;justify-content:space-between;height:38px;font-size:16px;color:#1d1b20}
.sw-md-dialog.sw-dd .sw-dd-date button{height:100%;padding:0 14px;border:0;background:transparent;font:inherit;font-weight:500;color:#713fce;cursor:pointer}
.sw-md-dialog.sw-dd .sw-dd-date input[type=date]{position:absolute;right:0;bottom:0;width:1px;height:1px;padding:0;border:0;opacity:0;pointer-events:none}

/* Buttons */
.sw-md-dialog.sw-dd .sw-md-actions{margin-top:28px;gap:8px}
.sw-md-dialog.sw-dd .sw-md-cancel{padding:10px 16px}
.sw-md-dialog.sw-dd .sw-md-save{height:40px;padding:0 22px;border-radius:14px}
</style>
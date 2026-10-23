{{-- --------------- --}}
{{-- Open shift dialog, toggled by shiftModal on the page root --}}
{{-- --------------- --}}
<div class="sw-shift-overlay" x-cloak x-show="shiftModal" x-transition.opacity @click.self="shiftModal=false"
     data-url="{{ route('manage.pos.cashbox.shifts.store') }}"
     x-data="{
        cash: '0',
        mobile: '0',
        error: '',
        busy: false,
        async submit() {
            if (this.busy) return;
            this.busy = true;
            this.error = '';
            try {
                const response = await fetch(this.$root.dataset.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '',
                    },
                    body: JSON.stringify({
                        opening_cash: this.cash === '' ? 0 : Number(this.cash),
                        opening_mobile_money: this.mobile === '' ? 0 : Number(this.mobile),
                    }),
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(Object.values(data.errors || {}).flat().join(' ') || data.message || 'Unable to open the shift.');
                }
                window.location.reload();
            } catch (e) {
                this.error = e.message;
                this.busy = false;
            }
        }
     }">
    <form class="sw-shift-dialog" role="dialog" aria-modal="true" aria-labelledby="sw-shift-title" @submit.prevent="submit()" @click.stop>
        <h3 id="sw-shift-title">Open shift</h3>
        <p>Enter your starting cash and Mpesa float.</p>
        <label for="sw-shift-cash">Starting cash</label>
        <input id="sw-shift-cash" type="number" inputmode="decimal" min="0" step="0.01" x-model="cash" @focus="$event.target.select()" autocomplete="off">
        <label for="sw-shift-mobile">Starting Mpesa</label>
        <input id="sw-shift-mobile" type="number" inputmode="decimal" min="0" step="0.01" x-model="mobile" @focus="$event.target.select()" autocomplete="off">
        <p class="sw-shift-error" x-show="error" x-text="error" role="alert"></p>
        <div class="sw-shift-actions">
            <button type="button" class="sw-shift-cancel" @click="shiftModal=false">Cancel</button>
            <button type="submit" class="sw-shift-submit" :disabled="busy">Open shift</button>
        </div>
    </form>
</div>
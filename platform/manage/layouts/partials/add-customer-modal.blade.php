{{-- Add customer dialog, toggled by customerModal on the page root --}}
<div class="sw-shift-overlay" x-cloak x-show="customerModal" x-transition.opacity @click.self="customerModal=false"
     @keydown.escape.window="customerModal=false"
     data-url="{{ route('manage.pos.customers.customers.store') }}"
     data-next="{{ route('manage.workspace.customers', ['page' => 'index']) }}"
     x-data="{
        f: { first_name: '', last_name: '', phone: '', email: '', credit_limit: '' },
        error: '',
        busy: false,
        async submit() {
            if (this.busy) return;
            this.error = '';
            if (!this.f.first_name.trim()) { this.error = 'First name is required.'; return; }
            this.busy = true;
            const body = {};
            for (const k in this.f) { const v = String(this.f[k]).trim(); if (v !== '') body[k] = k === 'credit_limit' ? Number(v) : v; }
            try {
                const response = await fetch(this.$root.dataset.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '',
                    },
                    body: JSON.stringify(body),
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(Object.values(data.errors || {}).flat().join(' ') || data.message || 'Unable to save the customer.');
                }
                const next = this.$root.dataset.next;
                if (window.location.pathname === new URL(next).pathname) { window.location.reload(); } else { window.location.href = next; }
            } catch (e) {
                this.error = e.message;
                this.busy = false;
            }
        }
     }"
     x-effect="if (customerModal) { error=''; busy=false; $nextTick(() => $refs.first && $refs.first.focus()); } else { f = { first_name: '', last_name: '', phone: '', email: '', credit_limit: '' }; }">
    <form class="sw-shift-dialog" role="dialog" aria-modal="true" aria-labelledby="sw-customer-title" @submit.prevent="submit()" @click.stop novalidate>
        <h3 id="sw-customer-title">Add customer</h3>
        <label for="sw-cust-first">First name</label>
        <input id="sw-cust-first" x-ref="first" x-model="f.first_name" type="text" maxlength="100" required autocomplete="off">
        <label for="sw-cust-last">Last name (optional)</label>
        <input id="sw-cust-last" x-model="f.last_name" type="text" maxlength="255" autocomplete="off">
        <label for="sw-cust-phone">Phone (optional)</label>
        <input id="sw-cust-phone" x-model="f.phone" type="tel" inputmode="tel" maxlength="255" autocomplete="off">
        <label for="sw-cust-email">Email (optional)</label>
        <input id="sw-cust-email" x-model="f.email" type="email" maxlength="255" autocomplete="off">
        <label for="sw-cust-credit">Credit limit (optional)</label>
        <input id="sw-cust-credit" x-model="f.credit_limit" type="number" inputmode="decimal" min="0" step="0.01" autocomplete="off">
        <p class="sw-shift-error" x-show="error" x-text="error" role="alert"></p>
        <div class="sw-shift-actions">
            <button type="button" class="sw-shift-cancel" @click="customerModal=false">Cancel</button>
            <button type="submit" class="sw-shift-submit" :disabled="busy">Save</button>
        </div>
    </form>
</div>
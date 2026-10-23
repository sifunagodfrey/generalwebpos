@extends('manage.layouts.workspace')
@section('title','Payment')
@section('heading','Payment')
@section('subtitle','Complete sale')
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-sales.css') }}">
<div class="sw-sales sw-sales-payment" x-data="salePayment({
    submitUrl: {{ Illuminate\Support\Js::from(route('manage.sales.checkout')) }},
    quoteUrl: {{ Illuminate\Support\Js::from(route('manage.sales.quote')) }},
    backUrl: {{ Illuminate\Support\Js::from(route('manage.workspace.sale')) }},
    customers: {{ Illuminate\Support\Js::from($customers) }},
    shiftOpen: {{ Illuminate\Support\Js::from((bool) $shift) }}
})" x-init="initPayment()">
  <div class="sw-payment-page">
   <div class="sw-payment-column">
    <template x-if="!lines.length"><div class="sw-sale-warning">No items in the cart. <a :href="backUrl">Return to New sale</a>.</div></template>
    <div x-show="lines.length" class="sw-payment-content">
      <section class="sw-payment-card"><h3>Items</h3>
       <template x-for="line in lines" :key="line.product_id+'-'+(line.variant_id||0)">
        <div class="sw-payment-item"><div><strong x-text="line.name"></strong><small x-text="line.quantity+' x '+money(unitNet(line))"></small></div><span x-text="money(lineTotal(line))"></span></div>
       </template>
      </section>
      <section class="sw-payment-card sw-payment-summary">
       <div class="sw-subtotal"><span>Total due</span><span x-text="money(total())"></span></div>
       <div class="sw-subtotal"><span>Rendered now</span><span x-text="money(applied())"></span></div>
       <div class="sw-subtotal" x-show="creditDue()>0"><span>To credit</span><strong x-text="money(creditDue())"></strong></div>
       <div class="sw-subtotal" x-show="changeDue()>0"><span>Change due</span><strong x-text="money(changeDue())"></strong></div>
      </section>
      <div class="sw-payment-form">
       <label class="sw-payment-label">Customer <span x-show="creditDue()<=0">(optional)</span><span x-show="creditDue()>0">(required for credit)</span></label>
        {{-- --------------- --}}
        {{-- Customer field with dropdown list --}}
        {{-- --------------- --}}
        <div class="sw-payment-customer-wrap" x-data="{ open: false, list: [] }"
             x-effect="list = customers.filter(c => { const t = customerSearch.trim().toLowerCase(); const s = selectedCustomer(); return !t || (s && customerLabel(s).toLowerCase() === t) || customerLabel(c).toLowerCase().includes(t); })"
             @click.outside="open=false" @keydown.escape="open=false">
         <div class="sw-payment-customer-field">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="8" r="3.5"/><path d="M3 19c0-3.2 2.7-5.5 6-5.5 1 0 1.9.2 2.7.6"/><circle cx="16.5" cy="16.5" r="2.5"/><path d="M18.4 18.4 21 21"/></svg>
          <input type="text" x-model="customerSearch" placeholder="Customer name or phone" autocomplete="off" @focus="open=true; $nextTick(() => $refs.menu.scrollIntoView({block:'nearest'}))" @input="open=true" @change="pickCustomerFromSearch()">
         </div>
         <ul class="sw-customer-menu" x-ref="menu" x-cloak x-show="open" role="listbox">
          <template x-for="customer in list" :key="customer.id">
           <li class="sw-customer-option" role="option" @mousedown.prevent @click="customerSearch = customerLabel(customer); pickCustomerFromSearch(); open = false">
            <strong x-text="[customer.first_name, customer.last_name].filter(Boolean).join(' ')"></strong>
            <small x-text="customer.phone"></small>
           </li>
          </template>
          <li class="sw-customer-empty" x-show="!list.length">No customers found</li>
         </ul>
        </div>
       <label class="sw-payment-label">Payment method</label>
       <div class="sw-payment-methods">
        <label><input type="radio" value="cash" name="payment_method" x-model="paymentMethod"> Cash</label>
        <label><input type="radio" value="card" name="payment_method" x-model="paymentMethod"> Card</label>
        <label><input type="radio" value="mpesa" name="payment_method" x-model="paymentMethod"> M-Pesa</label>
       </div>
       <label class="sw-payment-label" for="sw-tendered">Amount rendered</label><input id="sw-tendered" class="sw-payment-input" inputmode="decimal" type="number" min="0" step="0.01" x-model="tendered">
       <div x-show="paymentMethod==='card'"><label class="sw-payment-label" for="sw-card-reference">Card terminal reference</label><input id="sw-card-reference" class="sw-payment-input" type="text" maxlength="255" x-model="paymentReference" autocomplete="off"><small>Record only after the terminal confirms payment.</small></div>
       <div x-show="paymentMethod==='mpesa'" class="sw-sale-warning" role="status">M-Pesa verification is not connected to this Laravel installation. Please choose another method.</div>
       <div x-show="creditDue()>0" class="sw-payment-credit-info">
        <small>Outstanding amount will be recorded on the selected customer's credit account.</small>
        <label x-show="creditOverLimit()"><input type="checkbox" x-model="overLimitApproved"> Manager approval for exceeding the credit limit (server permission required)</label>
       </div>
       <template x-if="error"><div class="sw-sale-error" role="alert" x-text="error"></div></template>
      </div>
    </div>
    <div class="sw-payment-footer" x-show="lines.length"><button class="sw-sale-primary" type="button" :disabled="busy||paymentMethod==='mpesa'" @click="complete()" x-text="busy?'Saving…':'Complete sale'"></button></div>
   </div>
  </div>
</div>
<script src="{{ asset('js/suweka-sales.js') }}" defer></script>
@endsection

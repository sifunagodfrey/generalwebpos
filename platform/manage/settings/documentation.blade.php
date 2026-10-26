@extends('manage.layouts.workspace')
@section('title', 'Documentation')
@section('heading', 'Documentation')

@section('content')
@php
    $topics = [
        'getting-started' => [
            'title' => 'Getting started',
            'how' => 'Set up your shop, create staff accounts, add products and receive stock before trading. Each action belongs to the signed-in staff member.',
            'action' => 'Open Cash Box and start a shift with the cash and Mpesa opening balances. Return to New Sale to select products, collect payment and issue a receipt.',
            'check' => 'Use Backups regularly. A backup is your recovery copy; exporting a report is not a database backup. Keep a copy outside the trading device.',
        ],
        'sales-scanning' => [
            'title' => 'Sales & scanning',
            'how' => 'New Sale lets you search by product name or barcode. Connect a keyboard-mode scanner and focus the search field, or use the camera button on a supported phone or tablet.',
            'action' => 'Tap a product to add it. Out-of-stock items and quantities above current stock are blocked. The app checks stock again when completing checkout. Discounts change line totals and cart totals; permissions control who can apply them.',
            'check' => 'Hold a sale to finish later. Open Held Sales, inspect the items, then recall or delete it. Sale History opens recorded receipt details. If an item cannot be found, Raise Backorder records a request under Missing Stock.',
        ],
        'inventory-alerts' => [
            'title' => 'Inventory & alerts',
            'how' => 'Add products from the inventory sidebar. Enter prices, tax type and a barcode; manually typed barcodes are saved too. Choose a photo or take one on a phone. Photos are centre-cropped to a square, so keep the product centred.',
            'action' => 'Receive stock to increase available quantity. Stock Take records a physical count and adjusts stock; it is not a new delivery. For products with variants, count the correct size or other variant.',
            'check' => 'Set low-stock and expiry alert thresholds for each product or variant. Product Alerts has search and filter counts. Low stock means quantity reached the threshold; out of stock means no quantity remains. Expiry alerts need dated stock batches and an enabled expiry threshold.',
        ],
        'customers-credit' => [
            'title' => 'Customers & credit',
            'how' => 'Create customers from the sidebar and keep their names and contact details accurate. Select the customer when making a sale so the transaction is attached to the correct profile.',
            'action' => 'Credit balances represent amounts still owed. Review customer balances and invoices before taking a payment; a completed sale and a fully paid sale are different states.',
            'check' => 'Record later invoice payments through the document payment action. The payment belongs to the shift collecting it, even when the original invoice was created in an earlier shift.',
        ],
        'suppliers-orders' => [
            'title' => 'Suppliers & orders',
            'how' => 'Add supplier contact details from Add Supplier. Purchase orders document what the shop intends to buy; creating an order does not mean stock has arrived.',
            'action' => 'Inspect the order and record the quantities actually received. Partial deliveries should reflect the delivered quantities, not the full quantity ordered.',
            'check' => 'Review Pending Deliveries and Payments Owed to track outstanding goods and supplier balances. Check supplier details before recording a payment or delivery.',
        ],
        'cashbox-shifts' => [
            'title' => 'Cash Box & shifts',
            'how' => 'Opening float is the money present when a shift starts. Money received and paid out changes the expected balance. Cash and Mpesa are tracked separately.',
            'action' => 'Record Money In or Money Out with the correct payment method, category and reason. End a shift by entering the actual counted amounts. Variance is counted minus expected; zero means the figures reconcile.',
            'check' => 'Shift History lists open and closed shifts. Open a shift for its staff member, start/end times, balances, variances, sales and money movements. Closed shifts retain their recorded closing reconciliation.',
        ],
        'documents' => [
            'title' => 'Documents',
            'how' => 'A quotation proposes prices. A proforma invoice is a preliminary document. An invoice records the sale and amount due; a receipt records payment. A delivery note lists supplied goods.',
            'action' => 'Create quotations and proforma invoices from the sidebar. Check customer, items, quantities and payment instructions before sharing. Converted documents link to their next stage.',
            'check' => 'Branding and document settings control company details, logo, colors and payment options. Generate a fresh PDF after changing settings. Existing files already downloaded do not update automatically.',
        ],
        'reports-downloads' => [
            'title' => 'Reports & downloads',
            'how' => 'Select Today, This Week, This Month or a custom range. Sales reporting uses completed sales; opening, held and voided transactions do not count as completed revenue.',
            'action' => 'Download PDF for a readable report or Excel for analysis. The export identifies the selected period, summary and detail totals. Inventory balances are current snapshots, not historical stock balances.',
            'check' => 'Payment collections and sales revenue may differ because invoices can be paid later. Table totals cover the listed rows; top-seller tables show ranked products, not every product. Profit estimates depend on available cost data.',
        ],
        'staff-security' => [
            'title' => 'Staff & security',
            'how' => 'Every staff member should use their own password. Passwords must have 8-16 characters. Repeated incorrect attempts lock the account temporarily; review the message for remaining attempts and lock time.',
            'action' => 'Roles restrict access: cashiers trade, managers have additional shop/report permissions, and administrators manage settings and users. Never share an administrator account.',
            'check' => 'Password recovery uses the configured owner email and licence key. Keep these private. Action notifications provide quick links; the audit log records important actions with the acting staff member.',
        ],
        'backups-support' => [
            'title' => 'Backups & support',
            'how' => 'Create backups before major changes and store copies separately. Check backup time, size and who created it. Restore only after reviewing the backup; restoring replaces current data with the saved state.',
            'action' => 'If an error occurs, note what you clicked and the time. Support can use diagnostic logs to investigate. Logs cannot guarantee recovery from device power loss, operating-system termination or damaged storage.',
            'check' => 'Technical support: +254700577467 · support@suweka.com · suweka.com. Use Share diagnostic logs in Support to prepare a file for your chosen support channel. Review the file before sending.',
        ],
    ];

    $activeTopic = (string) request()->query('topic', 'getting-started');
    $current = $topics[$activeTopic] ?? $topics['getting-started'];
@endphp

<div class="max-w-6xl mx-auto pb-20 md:pb-6">
    <div class="doc-layout">
        <div class="w-full lg:w-64 flex-shrink-0 bg-white lg:bg-transparent rounded-2xl lg:rounded-none border border-gray-100 lg:border-none p-4 lg:p-0">
            <p class="text-xs font-bold text-[#713FCE] uppercase tracking-wider mb-4 px-3 hidden lg:block">Help Centre</p>

            <nav class="space-y-1">
                @foreach($topics as $key => $topic)
                    <a href="{{ route('manage.workspace.settings', ['page' => 'documentation', 'topic' => $key]) }}"
                       data-turbo="false"
                       class="block w-full text-left px-4 py-3 rounded-xl text-sm transition truncate {{ $activeTopic === $key ? 'bg-[#f4effc] text-[#713FCE] font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                        {{ $topic['title'] }}
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="doc-main">
            <div class="bg-[#f4effc] rounded-2xl p-8 mb-6">
                <i class="fa-solid fa-book-open text-[#713FCE] text-3xl mb-4"></i>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $current['title'] }}</h2>
                <p class="text-gray-500 text-lg">Understand the workflow, follow the steps, and check the result.</p>
            </div>

            <div class="space-y-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">How it works</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $current['how'] }}</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">What to do</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $current['action'] }}</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">What to check</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $current['check'] }}</p>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- --------------- --}}
{{-- Documentation layout (independent of Tailwind build) --}}
{{-- --------------- --}}
<style>
    /* --------------- */
    /* Documentation layout */
    /* --------------- */
    .doc-layout { display: flex; flex-direction: column; gap: 2rem; align-items: flex-start; }
    .doc-main { flex: 1 1 0%; width: 100%; min-width: 0; }
    @media (min-width: 1024px) {
        .doc-layout { flex-direction: row; }
        .doc-main { width: auto; }
    }
</style>
@endsection
@extends('manage.layouts.workspace')
@section('title', 'Data & Privacy')
@section('heading', 'Data & Privacy')

@section('content')
<div class="max-w-4xl mx-auto pb-20 md:pb-6 space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Data & Privacy</h2>
        <p class="text-gray-600">Your business records include products, stock, sales, customer balances, suppliers, staff activity and device identifiers. Treat these records and exported reports as confidential business information.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Where your data is stored</h2>
        <p class="text-gray-600">A standalone POS stores business data and product images on its device. In a paired store, the master records shared sales and stock. Terminals need the master connection for shared checkout. Device storage is not a substitute for an independent backup.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Access and device protection</h2>
        <p class="text-gray-600">Use separate staff accounts and the appropriate roles. Admin and manager permissions restrict management actions. Protect the device with a screen lock, operating-system updates and secure Wi-Fi. Approve only trusted terminals and unlink devices that should no longer have access. POS permissions do not prevent someone with access to the device files from copying or deleting them.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Backups and recovery</h2>
        <p class="text-gray-600">Local ZIP backups include the database and images. Keep an independent copy away from the POS and test restoring it on a compatible installation. Optional online backup needs internet and the configured Suweka backup service; it transfers the database rather than product images. A lost device cannot be recovered from a backup stored only on that same device. Restrict access to backup files and links.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Connected services</h2>
        <p class="text-gray-600">When enabled, licensing, online backup and payment features communicate with their configured services. M-Pesa processing can involve customer phone numbers, payment amounts and transaction references. Enter only the data required for your business workflow. Do not share payment credentials, pairing details or backup links with unauthorised people.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Deletion and requests</h2>
        <p class="text-gray-600">Deleting a product removes it from the active catalogue while retaining stock and historical records needed for accounting, returns and warranties. The business owner is responsible for appropriate customer-data access and retention. For questions about Suweka services or data handling, contact support@suweka.com. This notice describes the POS workflow; it does not promise encryption of every device file or automatic off-device backup.</p>
    </div>
</div>
@endsection
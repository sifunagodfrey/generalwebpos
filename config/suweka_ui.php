<?php
return [
 'sale'=>['label'=>'Sale','icon'=>'cash-register','desc'=>'Process sales and receipts','action'=>'Open Register','items'=>[['new','New sale','cash-register'],['held','Held sales','pause-circle'],['history','Sale history','history'],['backorders','Backorders','clock'],['returns','Returns','clipboard-check'],['discounts','Discounts','percent']]],
 'dashboard'=>['label'=>'Dashboard','icon'=>'table-columns','desc'=>'Overview and reports','action'=>'View Insights','items'=>[['index','Dashboard','table-columns'],['trends','Sales Trends','arrow-trend-up'],['alerts','Stock Alerts','triangle-exclamation'],['owing','Customers Owing','wallet']]],
 'inventory'=>['label'=>'Inventory','icon'=>'box-archive','desc'=>'Manage products and stock','items'=>[['products','Products','box-archive'],['create','New product','plus'],['alerts','Product Alerts','bell'],['categories','Categories','shapes'],['brands','Brands','tag'],['stocktake','Stock take','clipboard-check'],['backorders', 'Backorders', 'truck-fast'],['reports','Reports','chart-simple']]],
 'customers'=>['label'=>'Customers','icon'=>'users','desc'=>'Manage customer records','items'=>[['index','Customers','users'],['create','Add Customer','user-plus'],['ledger','Credit Ledger','wallet'],['reports','Reports','chart-simple']]],
 'suppliers'=>['label'=>'Suppliers','icon'=>'truck','desc'=>'Manage supplier records','items'=>[['index','Suppliers','truck'],['create','Add Supplier','cart-plus'],['orders','Purchase orders','receipt'],['deliveries','Pending deliveries','truck-fast'],['owed','Payments owed','file-invoice-dollar']]],
 'staff'=>['label'=>'Staff','icon'=>'id-card-clip','desc'=>'Manage staff accounts','items'=>[['index','All staff','id-card-clip'],['create','Add Staff','user-plus'],['performance','Performance','trophy'],['leave','Leave','calendar-xmark'],['reports','Reports','chart-simple']]],
    'cashbox' => [
        'label' => 'Cash Box',
        'icon'  => 'fa-wallet',
        'items' => [
            ['index',    'Cash Box',      'wallet'],
            ['history',  'Shift History', 'clock-rotate-left'],
            ['movement', 'Cash Movement', 'arrow-right-arrow-left'],
            ['income',   'Income',        'arrow-down'],
            ['expenses', 'Expenses',      'arrow-up'],
            ['reports',  'Reports',       'chart-simple'],
        ],
    ],
 'documents'=>['label'=>'Documents','icon'=>'folder','desc'=>'View and manage documents','action'=>'View Documents','items'=>[['index','All Documents','folder'],['quotations','Quotations','file-invoice-dollar'],['proformas','Proforma Invoices','file-lines'],['invoices','Invoices','receipt'],['delivery','Delivery Notes','truck'],['credit','Credit Notes','clipboard-plus']]],
 'reports'=>['label'=>'Reports','icon'=>'chart-simple','desc'=>'Sales and shift reports','action'=>'View Reports','items'=>[['index','Overview','table-cells-large'],['sales','Sales','chart-column'],['profit','Profit & Loss','arrow-trend-up'],['vat','VAT','file-invoice-dollar'],['products','Products','box-archive'],['staff','Staff','id-card-clip'],['shifts','Shifts','piggy-bank']]],
 'settings'=>['label'=>'Settings','icon'=>'gear','desc'=>'Shop and system settings','action'=>'Open Settings','items'=>[['network','Store Network','network-wired'],['vat','VAT','file-invoice-dollar'],['alerts','Alerts','bell'],['hardware','Hardware','print'],['mpesa','M-Pesa','mobile-screen'],['users','Users','id-card-clip'],['branding','Branding','store'],['backup','Backup','cloud-arrow-up'],['sample','Sample data','table-cells-large'],['documents','Documents','file-lines']]],
];

<?php

namespace App\Http\Controllers\Manage\Documents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentWorkspaceController extends Controller
{
    public function index(Request $request, string $page = 'index')
    {
        $modules = config('suweka_ui');
        $module = 'documents';
        $item = collect($modules[$module]['items'] ?? [])->first(fn ($v) => $v[0] === $page) ?? [$page, 'Documents', 'folder'];
        $search = trim((string) $request->input('q', ''));

        // 1. Issue Credit Note (Split View)
        if ($page === 'issue-credit-note') {
            $sales = DB::table('su_sales as s')
                ->leftJoin('su_customers as c', 'c.id', '=', 's.customer_id')
                ->where('s.status', 'completed')
                ->select('s.id', 's.total', 'c.first_name', 'c.last_name')
                ->orderByDesc('s.completed_at')
                ->limit(50)
                ->get();

            return view('manage.documents.issue-credit-note', compact('modules', 'module', 'page', 'item', 'sales', 'search'));
        }

        // 2. Credit Notes List
        if ($page === 'credit-notes') {
            $query = DB::table('su_returns_refunds as r')
                ->join('su_credit_notes as cn', 'cn.return_id', '=', 'r.id')
                ->leftJoin('su_sales as s', 's.id', '=', 'r.sale_id')
                ->leftJoin('su_customers as c', 'c.id', '=', 's.customer_id')
                ->select('r.id', 'r.sale_id', 'r.reason', 'r.created_at as returned_at', 'c.first_name', 'c.last_name', 'cn.item_name', 'cn.cash_refund', 'cn.credit_reversal', 'r.amount_refunded');

            if ($search !== '') {
                $query->where(function($q) use ($search) {
                    $q->where('r.id', 'like', "%{$search}%")
                      ->orWhere('c.first_name', 'like', "%{$search}%")
                      ->orWhere('c.last_name', 'like', "%{$search}%")
                      ->orWhere('cn.item_name', 'like', "%{$search}%");
                });
            }

            $records = $query->orderByDesc('r.id')->paginate(20)->withQueryString();

            return view('manage.documents.credit-notes', compact('modules', 'module', 'page', 'item', 'records', 'search'));
        }

        // 3. Type-Specific Views (Quotations, Proforma, Invoices, Delivery)
        if (in_array($page, ['quotations', 'proforma', 'invoices', 'delivery'])) {
            $typeMap = [
                'quotations' => 'quotation',
                'proforma'   => 'proforma',
                'invoices'   => 'invoice',
                'delivery'   => 'delivery_note',
            ];
            
            $query = DB::table('su_documents as d')
                ->leftJoin('su_customers as c', 'c.id', '=', 'd.customer_id')
                ->where('d.type', $typeMap[$page])
                ->select('d.id', 'd.type', 'd.status', 'd.total', 'd.created_at', 'c.first_name', 'c.last_name');

            if ($search !== '') {
                $query->where(function($q) use ($search) {
                    $q->where('d.id', 'like', "%{$search}%")
                      ->orWhere('c.first_name', 'like', "%{$search}%")
                      ->orWhere('c.last_name', 'like', "%{$search}%");
                });
            }

            $records = $query->orderByDesc('d.created_at')->paginate(20)->withQueryString();

            return view('manage.documents.type', compact('modules', 'module', 'page', 'item', 'records', 'search'));
        }

        // 4. All Documents
        $docQuery = DB::table('su_documents as d')
            ->leftJoin('su_customers as c', 'c.id', '=', 'd.customer_id')
            ->select('d.id', 'd.type', 'd.status', 'd.total', 'd.created_at', 'c.first_name', 'c.last_name');

        $cnQuery = DB::table('su_returns_refunds as r')
            ->join('su_credit_notes as cn', 'cn.return_id', '=', 'r.id')
            ->leftJoin('su_sales as s', 's.id', '=', 'r.sale_id')
            ->leftJoin('su_customers as c', 'c.id', '=', 's.customer_id')
            ->select('r.id', 'r.created_at as returned_at', 'c.first_name', 'c.last_name', 'cn.item_name', 'cn.cash_refund', 'cn.credit_reversal', 'r.amount_refunded');

        if ($search !== '') {
            $docQuery->where(function($q) use ($search) {
                $q->where('d.id', 'like', "%{$search}%")
                  ->orWhere('c.first_name', 'like', "%{$search}%")
                  ->orWhere('c.last_name', 'like', "%{$search}%");
            });

            $cnQuery->where(function($q) use ($search) {
                $q->where('r.id', 'like', "%{$search}%")
                  ->orWhere('c.first_name', 'like', "%{$search}%")
                  ->orWhere('c.last_name', 'like', "%{$search}%")
                  ->orWhere('cn.item_name', 'like', "%{$search}%");
            });
        }

        $documents = $docQuery->orderByDesc('d.created_at')->limit(100)->get();
        $creditNotes = $cnQuery->orderByDesc('r.id')->limit(50)->get();

        return view('manage.documents.index', compact('modules', 'module', 'page', 'item', 'documents', 'creditNotes', 'search'));
    }

    private function getTypeMap(): array
    {
        return [
            'quotations' => 'quotation',
            'proforma'   => 'proforma',
            'invoices'   => 'invoice',
            'delivery'   => 'delivery_note',
        ];
    }

    public function create(Request $request, string $type)
    {
        $typeMap = $this->getTypeMap();
        if (!isset($typeMap[$type])) abort(404);

        $docType = $typeMap[$type];
        $customers = DB::table('su_customers')->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'phone']);
        $products = DB::table('su_products')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'selling_price', 'tax_type']);

        $modules = config('suweka_ui');
        $module = 'documents';
        $page = $type;
        $item = collect($modules[$module]['items'])->first(fn($v) => $v[0] === $page) ?? [$page, 'Documents', 'folder'];
        
        $titles = [
            'quotations' => 'Quotation',
            'proforma'   => 'Proforma Invoice',
            'invoices'   => 'Invoice',
            'delivery'   => 'Delivery Note',
        ];
        $docTitle = $titles[$type];

        return view('manage.documents.create', compact('modules', 'module', 'page', 'item', 'docType', 'docTitle', 'customers', 'products'));
    }

    public function store(Request $request, string $type)
    {
        $typeMap = $this->getTypeMap();
        if (!isset($typeMap[$type])) abort(404);
        $docType = $typeMap[$type];

        $data = $request->validate([
            'customer_id' => 'nullable|exists:su_customers,id',
            'lines' => 'required|array|min:1',
            'lines.*.product_id' => 'required|exists:su_products,id',
            'lines.*.quantity' => 'required|numeric|min:0.01',
            'lines.*.unit_price' => 'required|numeric|min:0',
            'lines.*.tax_type' => 'required|in:standard_rated,zero_rated,exempt',
        ]);

        $staffId = DB::table('su_staff')->where('user_id', Auth::id())->value('id');
        if (!$staffId) {
            return back()->withErrors(['lines' => 'Your account must be linked to an active staff record.']);
        }

        $documentId = DB::transaction(function () use ($data, $docType, $staffId) {
            $subtotal = 0;
            $taxTotal = 0;
            $vatRate = (float) DB::table('su_settings')->where('setting_key', 'vat_rate')->value('setting_value') ?? 0;

            $lines = [];
            foreach ($data['lines'] as $line) {
                $product = DB::table('su_products')->where('id', $line['product_id'])->first();
                $lineTotal = $line['quantity'] * $line['unit_price'];
                $subtotal += $lineTotal;

                $taxAmount = 0;
                if ($line['tax_type'] === 'standard_rated' && $vatRate > 0) {
                    $taxAmount = round($lineTotal * ($vatRate / 100), 2);
                }
                $taxTotal += $taxAmount;

                $lines[] = [
                    'product_id' => $line['product_id'],
                    'name' => $product->name,
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'tax_type' => $line['tax_type'],
                    'tax_amount' => $taxAmount,
                    'line_total' => $lineTotal + $taxAmount,
                ];
            }

            $documentId = DB::table('su_documents')->insertGetId([
                'type' => $docType,
                'customer_id' => $data['customer_id'] ?? null,
                'staff_id' => $staffId,
                'status' => $docType === 'invoice' ? 'issued' : 'draft',
                'subtotal' => round($subtotal, 2),
                'discount_total' => 0,
                'tax_total' => round($taxTotal, 2),
                'total' => round($subtotal + $taxTotal, 2),
                'business_id' => null,
                'branch_id' => null,
                'created_at' => now(),
            ]);

            foreach ($lines as $line) {
                DB::table('su_document_items')->insert($line + [
                    'document_id' => $documentId,
                    'business_id' => null,
                    'branch_id' => null,
                ]);
            }

            return $documentId;
        });

        return redirect()->route('manage.workspace.documents', ['page' => $type])
            ->with('success', ucfirst(str_replace('_', ' ', $docType)) . ' #' . $documentId . ' created successfully.');
    }
}
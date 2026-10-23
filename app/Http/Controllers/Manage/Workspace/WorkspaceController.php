<?php
namespace App\Http\Controllers\Manage\Workspace;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class WorkspaceController extends Controller
{
    private function safeCount(string $table): int { return Schema::hasTable($table) ? DB::table($table)->count() : 0; }
    private function safeRows(string $table, int $limit=30) {
        return Schema::hasTable($table) ? DB::table($table)->orderByDesc('id')->limit($limit)->get() : collect();
    }
    public function home() {
        return view('manage.dashboard.index', ['counts'=>[
            'inventory'=>$this->safeCount('su_products'), 'customers'=>$this->safeCount('su_customers'),
            'suppliers'=>$this->safeCount('su_suppliers'), 'staff'=>$this->safeCount('su_staff'),
        ]]);
    }
    public function show(Request $request, string $page='index') {
        $module=explode('.', (string)$request->route()->getName())[2] ?? ''; 
        $modules=config('suweka_ui');
        abort_unless(isset($modules[$module]),404);
        $item=collect($modules[$module]['items'])->first(fn($i)=>$i[0]===$page);
        abort_unless($item!==null,404);
        $records=collect(); $stats=[]; $plot=[];
        // Query in MySQL before pagination, not only among the first loaded cards.
        $search = trim((string)$request->query('q', ''));
        $search = mb_substr($search, 0, 120);
        if (($module==='inventory' && $page==='products') || ($module==='customers' && $page==='index') || ($module==='suppliers' && $page==='index')) {
            $table = match($module) { 'inventory'=>'su_products', 'customers'=>'su_customers', default=>'su_suppliers' };
            $query=DB::table($table);
            if ($search !== '') {
                $escaped = addcslashes($search, '%_\\');
                $like = '%'.$escaped.'%';
                if ($module==='inventory') $query->where('name', 'like', $like);
                else $query->where(function($q) use ($like) {
                    $q->where('first_name','like',$like)->orWhere('last_name','like',$like)->orWhere('phone','like',$like);
                });
            }
            $records=$query->orderBy('id')->paginate(20)->withQueryString();
            return view('manage.workspace.page',compact('modules','module','page','item','records','stats','plot','search'));
        }
        if($module==='inventory' && in_array($page,['products','alerts','stocktake','create','categories','brands','backorders'])) {
            $table=match($page){'categories'=>'su_categories','brands'=>'su_brands','backorders'=>'su_backorders',default=>'su_products'};
            $records=$this->safeRows($table,100);
        } elseif($module==='sale') {
            $records=$this->safeRows($page==='new'?'su_products':'su_sales',100);
        } elseif($module==='customers') { $records=$this->safeRows($page==='ledger'?'su_credit_transactions':'su_customers',100); }
        elseif($module==='suppliers') { $records=$this->safeRows(in_array($page,['orders','deliveries'])?'su_purchase_orders':'su_suppliers',100); }
        elseif($module==='staff') { $records=$this->safeRows('su_staff',100); }
        elseif($module==='documents') { $records=$this->safeRows('su_documents',100); }
        elseif($module==='cashbox') { $records=$this->safeRows($page==='index'?'su_cash_box':($page==='shifts'?'su_shifts':'su_cash_box'),100); }
        if(in_array($module,['dashboard','reports','cashbox'])) {
            $stats=['sales'=>0,'transactions'=>0,'low_stock'=>0,'owing'=>0,'products'=>0,'staff'=>0,'shifts'=>0,'vat'=>0];
            if(Schema::hasTable('su_sales')) {
                $sales=DB::table('su_sales')->where('status','completed');
                $stats['sales']=(float)(clone $sales)->whereDate('completed_at',today())->sum('total');
                $stats['transactions']=(clone $sales)->whereDate('completed_at',today())->count();
                for($i=6;$i>=0;$i--) {
                    $date=today()->subDays($i);
                    $plot[]=['day'=>$date->format('d/m'),'value'=>(float)DB::table('su_sales')->where('status','completed')->whereDate('completed_at',$date)->sum('total')];
                }
                $stats['vat']=(float)(clone $sales)->sum('tax_total');
            }
            if(Schema::hasTable('su_products')) $stats['products']=$this->safeCount('su_products');
            if(Schema::hasTable('su_staff')) $stats['staff']=$this->safeCount('su_staff');
            if(Schema::hasTable('su_shifts')) $stats['shifts']=$this->safeCount('su_shifts');
            if(Schema::hasTable('su_customers')) $stats['owing']=(float)DB::table('su_customers')->sum('credit_balance');
            if(Schema::hasTable('su_stock_batches') && Schema::hasColumn('su_stock_batches','quantity_remaining')) $stats['low_stock']=DB::table('su_stock_batches')->where('quantity_remaining','<=',5)->count();
        }
        return view('manage.workspace.page',compact('modules','module','page','item','records','stats','plot'));
    }
}

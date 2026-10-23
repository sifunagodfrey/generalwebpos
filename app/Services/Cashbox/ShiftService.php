<?php
namespace App\Services\Cashbox;
use App\Models\Cashbox\Shift;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class ShiftService {
 public function open(int $userId, array $values): Shift {
  return DB::transaction(function () use ($userId,$values) {
   $staff = DB::table('su_staff')->where('user_id',$userId)->where('is_active',1)->lockForUpdate()->first();
   if (!$staff) throw ValidationException::withMessages(['staff'=>'Your account must be linked to an active POS staff record.']);
   if (Shift::query()->where('staff_id',$staff->id)->where('is_closed',false)->exists()) throw ValidationException::withMessages(['shift'=>'An open shift already exists.']);
   return Shift::query()->create(['staff_id'=>$staff->id,'opening_cash'=>$values['opening_cash'],'opening_mobile_money'=>$values['opening_mobile_money'],'opened_at'=>now(),'is_closed'=>false]);
  });
 }
 public function close(Shift $shift, array $values): Shift {
  return DB::transaction(function () use ($shift,$values) {
   $shift = Shift::query()->lockForUpdate()->findOrFail($shift->id);
   if ($shift->is_closed) throw ValidationException::withMessages(['shift'=>'This shift is already closed.']);
   // This is intentionally a provisional calculation; final reconciliation must include sale payments/refunds.
   $entries = DB::table('su_cash_box')->where('shift_id',$shift->id)->get(['direction','amount']);
   $netCents = 0;
   foreach ($entries as $entry) $netCents += (int) round(((float)$entry->amount)*100) * (strtolower($entry->direction)==='in'?1:-1);
   $expectedCash = number_format(((int) round(((float)$shift->opening_cash)*100)+$netCents)/100,2,'.','');
   $expectedMobile = (string)$shift->opening_mobile_money;
   $shift->fill(['closing_cash_counted'=>$values['closing_cash_counted'],'closing_mobile_money_counted'=>$values['closing_mobile_money_counted'], 'expected_cash'=>$expectedCash,'expected_mobile_money'=>$expectedMobile,'cash_variance'=>number_format(((int)round(((float)$values['closing_cash_counted'])*100)-(int)round(((float)$expectedCash)*100))/100,2,'.',''),'mobile_money_variance'=>number_format(((int)round(((float)$values['closing_mobile_money_counted'])*100)-(int)round(((float)$expectedMobile)*100))/100,2,'.',''),'closed_at'=>now(),'is_closed'=>true]);
   $shift->save(); return $shift->refresh();
  });
 }
}

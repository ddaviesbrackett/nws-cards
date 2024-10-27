<?php

namespace App\Livewire\Admin;

use App\Models\CutoffDate;
use App\Models\SchoolClass;
use Livewire\Attributes\On;
use Livewire\Component;

class ProfitForm extends Component
{
    public int $id;
    public float $saveon_cheque_value;
    public float $saveon_card_value;
    public float $coop_cheque_value;
    public float $coop_card_value;

    public string $dialogId;

    public function mount($dialogId = "prof-dialog")
    {
        $this->dialogId = $dialogId;
    }

    #[On('populate')]
    public function load(int $dateforprofit = null)
    {
        if (!is_null($dateforprofit)) {
            $exp = CutoffDate::find($dateforprofit);
            $this->id = $exp->id;
            $this->saveon_cheque_value = $exp->saveon_cheque_value;
            $this->saveon_card_value = $exp->saveon_card_value;
            $this->coop_cheque_value = $exp->coop_cheque_value;
            $this->coop_card_value = $exp->coop_card_value;
        } else {
            unset($this->id);
            unset($this->saveon_cheque_value);
            unset($this->saveon_card_value);
            unset($this->coop_cheque_value);
            unset($this->coop_card_value);
        }
    }

    private function generateProfits($date) {
        $saveon = 0.0;
        $coop = 0.0;
        if( ! empty($date->saveon_cheque_value) && ! empty($date->saveon_card_value))
        {
            $saveon = ($date->saveon_card_value - $date->saveon_cheque_value) / $date->saveon_card_value;
        }

        if( ! empty($date->coop_cheque_value) && ! empty($date->coop_card_value))
        {
            $coop = ($date->coop_card_value - $date->coop_cheque_value) / $date->coop_card_value;
        }

        return ['saveon'=>$saveon * 100, 'coop' => $coop * 100];
    }

    public function save()
    {
        if (isset($this->id)) {
            $cutoff = CutoffDate::find($this->id);
            $cutoff->saveon_cheque_value = $this->saveon_cheque_value;
            $cutoff->saveon_card_value = $this->saveon_card_value;
            $cutoff->coop_cheque_value = $this->coop_cheque_value;
            $cutoff->coop_card_value = $this->coop_card_value;

            //now update order profits on all orders in the cutoff
            $profits = $this->generateProfits($cutoff);
            $pacClassId = SchoolClass::where('bucketname', '=', 'pac')->pluck('id');
            $tuitionClassId = SchoolClass::where('bucketname', '=', 'tuitionreduction')->pluck('id');
            $cutoff->orders->load('user')->each(function($order) use ($profits, $pacClassId, $tuitionClassId) {
                $saveon = $order->saveon + $order->saveon_onetime;
                $coop = $order->coop + $order->coop_onetime;
                $profit = ($saveon * $profits['saveon']) + ($coop * $profits['coop']);
                
                //stripe takes its cut
                if($order->isCreditCard()) {
                    $profit -= ($saveon + $coop) * 2.38;
                    $profit -= 0.30;
                }
                $order->profit = $profit;

                $buckets = $order->schoolclasses()->count();

                $pac = 0;
                $tuitionreduction = 0;
                
                if($buckets > 2) {
                    $perBucket = $profit / ($buckets - 2); //pac and tuitionreduction don't count anymore
                    foreach($order->schoolclasses()->where('bucketname','<>', 'pac')->where('bucketname', '<>', 'tuitionreduction')->get() as $class)
                    {
                        $order->schoolclasses()->updateExistingPivot($class->id, ['profit' => $perBucket * $class->classsplit]);
                        $pac += $perBucket * $class->pacsplit;
                        $tuitionreduction += $perBucket * $class->tuitionsplit;
                    }
                }
                else
                {
                    $pac = $profit * 0.05;
                    $tuitionreduction = $profit * 0.95;
                }

                $order->schoolclasses()->updateExistingPivot($pacClassId, ['profit' => $pac]);
                $order->schoolclasses()->updateExistingPivot($tuitionClassId, ['profit' => $tuitionreduction]);
                $order->save();
            });

            $cutoff->save();
            session()->flash('status',  'Profit Calculated'); //TODO implement notifications
        }
        $this->reset();
        return $this->redirectRoute('admin-orders');
    }

    public function render()
    {
        return view('livewire.admin.profit-form');
    }
}

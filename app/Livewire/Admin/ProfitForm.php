<?php

namespace App\Livewire\Admin;

use App\Models\CutoffDate;
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

    public function save()
    {
        if (isset($this->id)) {
            $exp = CutoffDate::find($this->id);
            $exp->saveon_cheque_value = $this->saveon_cheque_value;
            $exp->saveon_card_value = $this->saveon_card_value;
            $exp->coop_cheque_value = $this->coop_cheque_value;
            $exp->coop_card_value = $this->coop_card_value;
            $exp->save();

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

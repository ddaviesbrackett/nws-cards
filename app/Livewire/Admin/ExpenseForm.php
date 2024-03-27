<?php

namespace App\Livewire\Admin;

use App\Models\Expense;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Livewire\Component;

class ExpenseForm extends Component
{
    public int $id;
    public Carbon $date;
    public string $description;
    public float $amount;
    public int $account;

    public string $dialogId;

    public function mount(Expense $expense = null, $dialogId = "exp-dialog")
    {
        //TODO populate from db model
        $this->dialogId = $dialogId;
    }

    public function save()
    {

    }

    public function render()
    {
        $schoolclasses = [];
        foreach (SchoolClass::all() as $sc) {
            $schoolclasses[$sc->name] = $sc->id;
        }
        return view('livewire.admin.expense-form')->with('schoolclasses',  $schoolclasses);
    }
}

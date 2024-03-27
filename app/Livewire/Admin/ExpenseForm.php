<?php

namespace App\Livewire\Admin;

use App\Models\Expense;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Livewire\Component;

class ExpenseForm extends Component
{
    public int $id;
    public Carbon $expense_date;
    public string $description;
    public float $amount;
    public int $class_id;

    public string $dialogId;

    public function mount(Expense $expense = null, $dialogId = "exp-dialog")
    {
        //TODO populate from db model
        $this->dialogId = $dialogId;
    }

    public function save()
    {
        Expense::create($this->only(['expense_date', 'description', 'amount', 'class_id']));

        session()->flash('status',  'Expense added.'); //TODO implement notifications
        return $this->redirectRoute('admin-expenses');
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

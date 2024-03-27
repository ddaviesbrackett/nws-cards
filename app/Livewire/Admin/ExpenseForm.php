<?php

namespace App\Livewire\Admin;

use App\Models\Expense;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class ExpenseForm extends Component
{
    public int $id;
    public Carbon $expense_date;
    public string $description;
    public float $amount;
    public int $class_id;

    public string $dialogId;

    public function mount($dialogId = "exp-dialog")
    {
        $this->dialogId = $dialogId;
    }

    #[On('populate')] 
    public function load(int $id = null)
    {
        $exp = Expense::find($id);
        $this->id = $exp->id;
        $this->expense_date = $exp->expense_date;
        $this->description = $exp->description;
        $this->amount = $exp->amount;
        $this->class_id = $exp->class_id;
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

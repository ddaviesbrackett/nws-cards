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
    public string $expense_date;
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
        if (!is_null($id)) {
            $exp = Expense::find($id);
            $this->id = $exp->id;
            $this->expense_date = $exp->expense_date->format('Y-m-d');
            $this->description = $exp->description;
            $this->amount = $exp->amount;
            $this->class_id = $exp->class_id;
        } else {
            unset($this->id);
            unset($this->expense_date);
            unset($this->description);
            unset($this->amount);
            $this->class_id = 1; //default
        }
    }

    public function save()
    {
        if (isset($this->id)) {
            $exp = Expense::find($this->id);
            $exp->expense_date = new Carbon($this->expense_date);
            $exp->description = $this->description;
            $exp->amount = $this->amount;
            $exp->class_id = $this->class_id;
            $exp->save();

            session()->flash('status',  'Expense added.'); //TODO implement notifications
        } else {
            $values = $this->only(['expense_date', 'description', 'amount', 'class_id']);
            $values['expense_date'] = new Carbon($values['expense_date']);
            Expense::create($values);

            session()->flash('status',  'Expense added.'); //TODO implement notifications
        }
        $this->reset();
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

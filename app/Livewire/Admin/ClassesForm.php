<?php

namespace App\Livewire\Admin;

use App\Models\SchoolClass;
use Livewire\Attributes\On;
use Livewire\Component;

class ClassesForm extends Component
{
    public int $id;
    public string $name;
    public string $bucketname;
    public int $displayorder;
    public bool $current;
    public int $enrolment;

    public string $dialogId;

    public function mount($dialogId = "class-dialog"){
        $this->dialogId = $dialogId;
    }

    #[On('populate')]
    public function load(?int $id = null)
    {
        if (!is_null($id)) {
            $class = SchoolClass::find($id);
            $this->id = $class->id;
            $this->name = $class->name;
            $this->bucketname = $class->bucketname;
            $this->displayorder = $class->displayorder;
            $this->current = $class->current;
            $this->enrolment = $class->enrolment;
        } else {
            unset($this->id);
            unset($this->name);
            unset($this->bucketname);
            unset($this->displayorder);
            unset($this->current);
            unset($this->enrolment);
        }
    }

    public function save()
    {
        if (isset($this->id)) {
            $class = SchoolClass::find($this->id);
            $class->id = $this->id;
            $class->name = $this->name;
            $class->bucketname = $this->bucketname;
            $class->displayorder = $this->displayorder;
            $class->current = $this->current;
            $class->enrolment = $this->enrolment;
            $class->save();

            session()->flash('status',  'Class edited.'); //TODO implement notifications
        } else {
            $values = $this->only(['name', 'bucketname', 'displayorder', 'enrolment']);
            SchoolClass::create($values);

            session()->flash('status',  'Class added.'); //TODO implement notifications
        }
        $this->reset();
        return $this->redirectRoute('admin-classes');
    }

    public function render()
    {
        return view('livewire.admin.classes-form');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\SchoolClass;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    function expenses(): View
    {
        return $this->result();
    }

    function postExpenses(): View
    {
        return view('admin.expenses.show)');
    }

    private function result($extra = [])
    {
        $expenses = Expense::orderby('expense_date', 'desc')->with('schoolclass')->get();
        $schoolclasses = [];
        foreach (SchoolClass::all() as $sc) {
            $schoolclasses[$sc->id] = $sc->name;
        }
        return view('admin.expenses.show', array_merge(['model' => $expenses, 'schoolclasses' => $schoolclasses], $extra));
    }
}

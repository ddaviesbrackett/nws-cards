<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class ExpenseController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['admin'];
    }
    
    public function expenses(): View
    {
        return $this->result();
    }

    public function deleteExpense(int $id): RedirectResponse
    {
        Expense::find($id)->delete();
        return redirect()->route('admin-expenses');
    }

    private function result($extra = [])
    {
        $expenses = Expense::orderby('expense_date', 'desc')->with('schoolclass')->get();
        return view('admin.expenses.show', array_merge(['model' => $expenses], $extra));
    }
}

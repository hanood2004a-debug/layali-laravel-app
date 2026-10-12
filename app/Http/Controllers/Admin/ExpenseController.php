<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $month = (string) $request->query('month', now()->format('Y-m'));
        try {
            $start = Carbon::createFromFormat('!Y-m', $month)->startOfMonth();
        } catch (\Throwable $e) {
            $start = now()->startOfMonth();
        }
        $month = $start->format('Y-m');

        $expenses = Expense::whereBetween('spent_at', [$start->toDateString(), $start->copy()->endOfMonth()->toDateString()])
            ->orderByDesc('spent_at')->orderByDesc('id')->get();

        return view('admin.expenses', [
            'expenses'   => $expenses,
            'month'      => $month,
            'total'      => (float) $expenses->sum('amount'),
            'categories' => Expense::CATEGORIES,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'spent_at' => 'required|date',
            'title'    => 'required|string|max:120',
            'category' => ['required', Rule::in(Expense::CATEGORIES)],
            'amount'   => 'required|numeric|min:0.01|max:999999',
            'notes'    => 'nullable|string|max:255',
        ]);
        Expense::create($data);

        return back()->with('ok', 'تمت إضافة المصروف');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return back()->with('ok', 'تم حذف المصروف');
    }
}

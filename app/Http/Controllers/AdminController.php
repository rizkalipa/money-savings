<?php

namespace App\Http\Controllers;

use App\Models\SavingHistory;
use App\Models\Savings;
use Illuminate\Filesystem\Cache;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        $savings = Savings::find(auth()->user()->id);
        $savingHistory = SavingHistory::latest('id')->limit(10)->get();
        $totalSaving = $savings ? $savings->sum('total_balance') : 0;

        return view('admin.index', compact('savings', 'savingHistory', 'totalSaving'));
    }

    public function addSaving(Request $request) {
        $request->validate([
            'savingAmount' => 'required|min:1'
        ]);

        $request = $request->except('_token');
        $latestSaving = SavingHistory::where('savings_id', auth()->user()->id)->latest('id')->first();
        $typeActivity = $request['typeActivity'] == '1' ? 'revenue' : 'expense';
        $savingAmount = $request['savingAmount'];
        $savingRate = 100;
        $savings = auth()->user()->savings();
        $dataSavings = $savings->first();
        $isIncrease = true;

        if ($typeActivity == 'revenue') {
            $totalBalance = $dataSavings->total_balance + $savingAmount;
        } else {
            $totalBalance = $dataSavings->total_balance - $savingAmount;
        }

        if ($latestSaving) {
            if ($latestSaving->amount < $savingAmount) {
                $rateFromLatest = $savingAmount - $latestSaving->amount;
                $savingRate = round(($rateFromLatest / $latestSaving->amount) * 100, 2);
                $isIncrease = true;
            } else {
                $rateFromLatest = ($savingAmount / $latestSaving->amount) * 100;
                $savingRate = round($rateFromLatest - 100, 2);
                $isIncrease = false;
            }
        }

        $savings->update([
            'total_balance' => $totalBalance,
            'last_activity' => $typeActivity,
            'last_saving' => $savingAmount,
            'saving_percentage' => $savingRate
        ]);

        SavingHistory::create([
            'amount' => $savingAmount,
            'type' => $typeActivity,
            'note' => $request['notes'],
            'saving_rate'=> round($savingRate, 1),
            'remaining_target' => 0,
            'total_amount' => 0,
            'savings_id' => auth()->user()->id,
            'is_increase' => $isIncrease
        ]);

        return redirect()->route('admin_index');
    }

    public function listSaving() {
        $savingHistory = SavingHistory::latest('id')->paginate(10);

        return view('admin.list-saving', compact('savingHistory'));
    }
}

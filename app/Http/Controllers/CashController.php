<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CashController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->middleware("auth");
        $this->middleware("\App\Http\Middleware\CheckType");
    }

    private function setDataArr()
    {
        //Trans table
        $this->data['todayTrans'] = Cash::with("dash_user")->whereDate('created_at', Carbon::today())->orderByDesc('id')->get();
        $this->data['todayTitle'] = "Today's Transactions";
        $this->data['todaySubtitle'] = "Check all transactions from the starting of today " . Carbon::today()->format('d/M/Y');
        $this->data['todayCols'] = ['Date', 'User', 'Title', 'In', 'Out', 'Balance', 'Comment'];
        $this->data['todayAtts'] = [
            ['date' => ['att' => 'created_at']],
            ['foreign' => ['dash_user', 'DASH_USNM']],
            'CASH_DESC',
            ["number" => ['att' => 'CASH_IN', 'nums' => 2]],
            ["number" => ['att' => 'CASH_OUT', 'nums' => 2]],
            ["number" => ['att' => 'CASH_BLNC', 'nums' => 2]],
            ["comment" => ['att' => 'CASH_CMNT']],
        ];
        //Trans table
        $this->data['trans'] = Cash::with("dash_user")->orderByDesc('id')->limit(300)->get();
        $this->data['transTitle'] = "More Transactions";
        $this->data['transSubtitle'] = "Check Latest 300 cash transaction";
        $this->data['transCols'] = ['Date', 'User', 'Title', 'In', 'Out', 'Balance', 'Comment'];
        $this->data['transAtts'] = [
            ['date' => ['att' => 'created_at']],
            ['foreign' => ['dash_user', 'DASH_USNM']],
            'CASH_DESC',
            ["number" => ['att' => 'CASH_IN', 'nums' => 2]],
            ["number" => ['att' => 'CASH_OUT', 'nums' => 2]],
            ["number" => ['att' => 'CASH_BLNC', 'nums' => 2]],
            ["comment" => ['att' => 'CASH_CMNT']],
        ];

        $this->data['formURL'] = url('cash/insert');
        $this->data['formTitle'] = "Add Cash Transaction";
        $this->data['balance'] = Cash::currentBalance();
        $this->data['paidToday'] = Cash::paidToday();
        $this->data['collectedToday'] = Cash::collectedToday();
        $this->data['startingBalance'] = Cash::yesterdayBalance();
    }

    public function home()
    {
        $this->setDataArr();
        return view('accounts.cash', $this->data);
    }

    public function insert(Request $request){
        $request->validate([
            "title"              => "required",
            "in"              => "required|numeric",
            "out"              => "required|numeric",
        ]);
        Cash::entry($request->title, $request->in, $request->out, $request->comment);
        return redirect("accounts/cash");
    }
}

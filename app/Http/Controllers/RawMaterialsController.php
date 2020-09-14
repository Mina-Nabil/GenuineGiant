<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\RawInventory;
use App\Models\RawInvoice;
use App\Models\RawMaterial;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RawMaterialsController extends Controller
{
    protected $data;
    protected $homeURL = 'rawmaterials/show';

    private function initDataArr()
    {
        $this->data['items'] = RawMaterial::all();
        $this->data['title'] = "Production Raw Materials";
        $this->data['subTitle'] = "Manage all Raw Materials used in production";
        $this->data['cols'] = ['Raw Material', 'Arabic Name', 'Reference Cost', 'Current Cost per KG', 'Stock', 'Edit'];
        $this->data['atts'] = [
            'RWMT_NAME', 'RWMT_ARBC_NAME',
            ["number" => ['att' => 'RWMT_ESTM_COST', 'nums' => 2]],
            ["number" => ['att' => 'RWMT_COST', 'nums' => 2]],
            ["number" => ['att' => 'RWMT_BLNC', 'nums' => 2]],
            ['edit' => ['url' => 'rawmaterials/edit/', 'att' => 'id']],
        ];
        $this->data['homeURL'] = $this->homeURL;
    }

    private function initInvoicesArr()
    {
        $this->data['latestInvoices'] = RawInvoice::latest()->limit(300)->get();
        $this->data['latestTitle'] = "Latest Invoices";
        $this->data['latestSubTitle'] = "Check last 300 Raw Materials Invoices";


        $this->data['todayInvoices'] = RawInvoice::with('supplier')->whereDate('created_at', Carbon::today())->get();
        $this->data['todayTitle'] = "Today's RawMaterials Purchases";
        $this->data['todaySubTitle'] = "Check all Raw Materials Invoices registered on " . Carbon::today()->format('d/M/Y');

        //Same cols and atts for both
        $this->data['cols'] = ['#', 'Date', 'Supplier Name', 'KGs', 'Cost', 'Paid'];
        $this->data['atts'] = [
            'id',
            ['date' => ['att' => 'created_at']],
            ['foreignUrl' => ['suppliers/profile', 'RINC_SUPP_ID', 'supplier', 'SUPP_NAME']],
            ["number" => ['att' => 'RINC_KGS', 'nums' => 2]],
            ["number" => ['att' => 'RINC_COST', 'nums' => 2]],
            ["number" => ['att' => 'RINC_PAID', 'nums' => 2]],
        ];
        //form
        $this->data['formTitle'] = "Add New Invoice";
        $this->data['formURL'] = url('invoice/insert');
        $this->data['suppliers'] = Supplier::all();
        $this->data['raws'] = RawMaterial::all();

        //totals
        $carbonDate = Carbon::today();
        $this->data['paidToday'] = RawInvoice::whereDate('created_at', $carbonDate)->sum('RINC_PAID');
        $this->data['paidMonth'] = RawInvoice::whereYear('created_at', $carbonDate->format('Y'))->whereMonth('created_at', $carbonDate->format('m'))->sum('RINC_PAID');
    }

    public function __construct()
    {
        $this->middleware("auth");
    }

    public function invoices()
    {
        $this->initInvoicesArr();
        return view('raw.invoices', $this->data);
    }

    public function stock()
    {

        //Stock table
        $data['raws'] = RawMaterial::where('RWMT_BLNC', '>', 0)->get();
        $data['rawTitle'] = "Stock List";
        $data['rawSubtitle'] = "Check Current Raw Materials kept in Stock";
        $data['rawCols'] = ['Raw Material', 'Arabic Name', 'Reference Cost', 'Current Cost per KG', 'Stock'];
        $data['rawAtts'] = [
            'RWMT_NAME', 'RWMT_ARBC_NAME',
            ["number" => ['att' => 'RWMT_ESTM_COST', 'nums' => 2]],
            ["number" => ['att' => 'RWMT_COST', 'nums' => 2]],
            ["number" => ['att' => 'RWMT_BLNC', 'nums' => 2]],
        ];

        //Trans table
        $data['trans'] = RawInventory::with("raw_material", "dash_user", 'supplier')->orderByDesc('id')->limit(100)->get();
        $data['transTitle'] = "Transactions";
        $data['transSubtitle'] = "Check Latest 100 record from the Inventory Transactions";
        $data['transCols'] = ['Date', 'User', 'Supplier', 'Raw', 'In', 'Out', 'Price', 'Balance', 'Comment'];
        $data['transAtts'] = [
            ['date' => ['att' => 'created_at']],
            ['foreign' => ['dash_user', 'DASH_USNM']],
            ['foreign' => ['supplier', 'SUPP_NAME']],
            ['foreign' => ['raw_material', 'RWMT_NAME']],
            ["number" => ['att' => 'RINV_IN', 'nums' => 2]],
            ["number" => ['att' => 'RINV_OUT', 'nums' => 2]],
            ["number" => ['att' => 'RINV_PRCE', 'nums' => 2]],
            ["number" => ['att' => 'RINV_BLNC', 'nums' => 2]],
            ["comment" => ['att' => 'RINV_CMNT']],
        ];

        //Summary
        $data['totalKG'] = $data['raws']->sum('RWMT_BLNC');
        $data['averagePrice'] = $data['raws']->average('RWMT_COST');
        $data['totalCost'] = $data['totalKG'] * $data['averagePrice'];


        return view('raw.stock', $data);
    }

    public function entry()
    {
        $data['raws'] = RawMaterial::all();
        $data['suppliers'] = Supplier::all();
        $data['formTitle'] = "Add New Raw Material Entry";
        $data['formURL'] = url("rawmaterials/entry/insert");
        return view("raw.entry", $data);
    }

    public function insertEntry(Request $request)
    {
        $request->validate([
            "in" => "nullable|numeric",
            "price" => "nullable|numeric",
            "out" => "nullable|numeric",
            "supplier" => "nullable|numeric",
            "raw" => "required|exists:raw_materials,id"
        ]);
        $raw = RawMaterial::findOrFail($request->raw);
        $raw->addEntry($request->in, $request->out, $request->supplier, $request->price, $request->comment);
        return redirect("rawmaterials/stock");
    }

    public function insertInvoice(Request $request)
    {
        $request->validate([
            'supplier' => 'required|exists:suppliers,id',
            'delivery' => 'required|numeric',
            'payment' => 'required|numeric'
        ]);

        $invoice = new RawInvoice();
        $invoice->RINC_SUPP_ID = $request->supplier;
        $invoice->RINC_DASH_ID = Auth::id();
        $invoice->RINC_PAID = $request->payment;
        $invoice->RINC_DLVR_FEES = $request->delivery;
        $invoice->RINC_CMNT = $request->comment;

        DB::transaction(function () use ($invoice, $request) {
            $invoice->save();
            $totalPrice = 0;
            $totalKGs = 0;
            foreach ($request->raw as $i => $row) {
                $rawItem = RawMaterial::findOrFail($row);
                $rawItem->addEntry($request->in[$i], 0, $invoice->RINC_SUPP_ID, $request->price[$i], "Invoice Entry", $invoice->id, false);
                $rawItem->save();
                $totalKGs += $request->in[$i];
                $totalPrice += $request->price[$i] * $request->in[$i];
            }
            $invoice->RINC_KGS = $totalKGs;
            $invoice->RINC_COST = $totalPrice;
            $supplier =  Supplier::findOrFail($request->supplier);
            $supplier->addInvoice($invoice->RINC_COST, "Invoice Number " . $invoice->id);
            if($request->payment > 0){
                Cash::entry("Invoice# " . $invoice->id . " Payment to " . $supplier->SUPP_NAME, 0, $request->payment, "Added Automatically from invoices page");
                $supplier->pay($request->payment, "Invoice# " . $invoice->id . " Payment");
            }
            $invoice->save();
        });
        return redirect('accounts/invoices');
    }


    //////////////REST
    public function home()
    {
        $this->initDataArr();
        $this->data['formTitle'] = "Add RawMaterial";
        $this->data['formURL'] = "rawmaterials/insert";
        $this->data['isCancel'] = false;
        return view('settings.rawmaterial', $this->data);
    }

    public function edit($id)
    {
        $this->initDataArr();
        $this->data['raw'] = RawMaterial::findOrFail($id);
        $this->data['formTitle'] = "Edit Raw Material ( " . $this->data['raw']->RWMT_NAME . " )";
        $this->data['formURL'] = "rawmaterials/update";
        $this->data['isCancel'] = false;
        return view('settings.rawmaterial', $this->data);
    }

    public function insert(Request $request)
    {



        $request->validate([
            "name"          => "required|unique:raw_materials,RWMT_NAME",
        ]);

        $rawmaterial = new RawMaterial();
        $rawmaterial->RWMT_NAME = $request->name;
        $rawmaterial->RWMT_ARBC_NAME = $request->arbcName;
        $rawmaterial->RWMT_COST = $request->cost;
        $rawmaterial->RWMT_ESTM_COST = $request->estimated;
        $rawmaterial->RWMT_BLNC = $request->balance;
        $rawmaterial->save();

        return redirect($this->homeURL);
    }

    public function update(Request $request)
    {
        $request->validate([
            "id" => "required",
        ]);
        $rawmaterial = RawMaterial::findOrFail($request->id);

        $request->validate([
            "name" => ["required",  Rule::unique('raw_materials', "RWMT_NAME")->ignore($rawmaterial->RWMT_NAME, "RWMT_NAME"),],
        ]);

        $rawmaterial->RWMT_NAME = $request->name;
        $rawmaterial->RWMT_ARBC_NAME = $request->arbcName;
        $rawmaterial->RWMT_COST = $request->cost;
        $rawmaterial->RWMT_ESTM_COST = $request->estimated;
        $rawmaterial->RWMT_BLNC = $request->balance;

        $rawmaterial->save();

        return redirect($this->homeURL);
    }
}

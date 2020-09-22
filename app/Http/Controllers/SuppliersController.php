<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Gender;
use App\Models\Order;
use App\Models\RawInventory;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SuppliersController extends Controller
{
    protected $data;
    protected $homeURL = "suppliers/show/all";

    public function __construct()
    {
        $this->middleware("auth");
        $this->middleware("\App\Http\Middleware\CheckType");
    }

    private function initHomeArr($type = -1) // 0 all - 1 latest - 2 top
    {

        $this->data['title'] = "All Saved Suppliers";
        $this->data['items'] = Supplier::all();

        $this->data['subTitle'] = "Manage Suppliers";
        $this->data['cols'] = ['id', 'Full Name', 'Mob#', 'Balance', 'Supply'];
        $this->data['atts'] = [
            'id',
            ['attUrl' => ["url" => 'suppliers/profile', "urlAtt" => 'id', "shownAtt" =>  "SUPP_NAME"]],
            'SUPP_MOBN',
            ['number' => ['att' => 'SUPP_BLNC']],
            ['comment' => ['att' => 'SUPP_BSNS']]
        ];
        $this->data['homeURL'] = $this->homeURL;
    }

    private function initTransArr() // 0 all - 1 latest - 2 top
    {
        //Pay table
        $this->data['title'] = "All Suppliers Transactions";
        $this->data['items'] = SupplierPayment::with('supplier', 'dash_user')->orderByDesc('id')->get();
        $this->data['subTitle'] = "Check Latest payments all suppliers ";
        $this->data['cols'] = ['Date', 'Supplier', 'Paid By', 'In', 'Out', 'Balance', 'Comment'];
        $this->data['atts'] = [
            ['date' => ['att' => 'created_at']],
            ['foreign' => ['supplier', 'SUPP_NAME']],
            ['foreign' => ['dash_user', 'DASH_USNM']],
            ["number" => ['att' => 'SPPY_RCVD', 'nums' => 2]],
            ["number" => ['att' => 'SPPY_PAID', 'nums' => 2]],
            ["number" => ['att' => 'SPPY_BLNC', 'nums' => 2]],
            ["comment" => ['att' => 'SPPY_CMNT']],
        ];

        $this->data['homeURL'] = $this->homeURL;
    }

    private function initAddArr($supplierID = -1)
    {
        if ($supplierID != -1) {
            $this->data['supplier'] = Supplier::findOrFail($supplierID);
            $this->data['formURL'] = "suppliers/update";
        } else {
            $this->data['formURL'] = "suppliers/insert/";
        }
        $this->data['raws'] = RawMaterial::all();
        $this->data['formTitle'] = "Add New Supplier";
        $this->data['isCancel'] = true;
        $this->data['homeURL'] = $this->homeURL;
    }

    private function initProfileArr($id)
    {
        //Stock table
        $this->data['supplier'] = Supplier::findOrFail($id);
        $this->data['supplies'] =  $this->data['supplier']->getRegisteredRawMaterials();
        $this->data['bought'] = $this->data['supplier']->getSuppliedRawMaterials();
        $this->data['rawTitle'] = "Supplied Raw Materials";
        $this->data['rawSubtitle'] = "Check all Raw Materials supplied by " . $this->data['supplier']->SUPP_NAME;
        $this->data['rawCols'] = ['Raw Material', 'Total KGs', 'Supplier Cost per KG'];
        $this->data['rawAtts'] = [
            'RWMT_NAME',
            ["number" => ['att' => 'kgSupplied', 'nums' => 2]],
            ["number" => ['att' => 'avgPrice',   'nums' => 2]],
        ];

        //Trans table
        $this->data['trans'] = RawInventory::where('RINV_SUPP_ID', '=', $this->data['supplier']->id)->with("raw_material", "dash_user", 'supplier')
            ->orderByDesc('raw_inventory.id')->get();
        $this->data['transTitle'] = "Transactions";
        $this->data['transSubtitle'] = "Check Latest entry records for " . $this->data['supplier']->SUPP_NAME;
        $this->data['transCols'] = ['Date', 'User', 'Supplier', 'Raw', 'In', 'Out', 'Price', 'KG Balance', 'Comment'];
        $this->data['transAtts'] = [
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

        //Pay table
        $this->data['pays'] = SupplierPayment::where('SPPY_SUPP_ID', '=', $this->data['supplier']->id)
            ->orderByDesc('id')->get();
        $this->data['payTitle'] = "Payments";
        $this->data['paySubtitle'] = "Check Latest payments for " . $this->data['supplier']->SUPP_NAME;
        $this->data['payCols'] = ['Date', 'Paid By', 'In', 'Out', 'Balance', 'Comment'];
        $this->data['payAtts'] = [
            ['date' => ['att' => 'created_at']],
            ['foreign' => ['dash_user', 'DASH_USNM']],
            ["number" => ['att' => 'SPPY_RCVD', 'nums' => 2]],
            ["number" => ['att' => 'SPPY_PAID', 'nums' => 2]],
            ["number" => ['att' => 'SPPY_BLNC', 'nums' => 2]],
            ["comment" => ['att' => 'SPPY_CMNT']],
        ];

        //Summary
        $this->data['balance'] = $this->data['supplier']->SUPP_BLNC;
        $this->data['totalKG'] = $this->data['trans']->sum('RINV_IN');
        $this->data['totalPaid'] = $this->data['pays']->sum('SPPY_PAID');

        //payment form
        $this->data['formTitle'] = "Add Payment";
        $this->data['formURL'] = url('suppliers/pay');

        //info data
        $this->data['infoFormURL'] = url('suppliers/update');
        $this->data['infoFormTitle'] = "Manage Supplier Info";
        $this->data['raws'] = RawMaterial::all();
        $this->data['isCancel'] = false;
    }

    public function home()
    {
        $this->initHomeArr(0);
        return view("suppliers.table", $this->data);
    }

    public function trans()
    {
        $this->initTransArr();
        return view("suppliers.table", $this->data);
    }

    public function add()
    {
        $this->initAddArr();
        return view("suppliers.add", $this->data);
    }

    public function edit($id)
    {
        $this->initAddArr($id);
        return view("suppliers.add", $this->data);
    }

    public function profile($id)
    {
        $this->initProfileArr($id);
        return view("suppliers.profile", $this->data);
    }

    public function pay(Request $request)
    {
        $request->validate([
            "amount"              => "required|numeric",
            "id"               => "required|exists:suppliers,id",
        ]);
        $supplier = Supplier::findOrFail($request->id);
        $supplier->pay($request->amount, $request->comment);
        return redirect("suppliers/profile/" . $supplier->id);
    }

    public function insert(Request $request)
    {
        $request->validate([
            "name"              => "required",
            "mob"               => "required|numeric",
        ]);

        $supplier = new Supplier();
        $supplier->SUPP_NAME = $request->name;
        $supplier->SUPP_MOBN = $request->mob;
        $supplier->SUPP_BLNC = $request->balance ?? 0;
        $supplies = array();
        foreach($request->raw as $i => $raw){
            $supplies[$raw] = ['SPLS_PRCE' => $request->price[$i]];
        }
        DB::transaction(function () use ($supplier, $supplies){
            $supplier->save();
            $supplier->supplies()->sync($supplies);
        });

        return redirect("suppliers/profile/" . $supplier->id);
    }

    public function update(Request $request)
    {
        $request->validate([
            "id"          => "required",
        ]);
        $supplier = Supplier::findOrFail($request->id);
        $request->validate([
            "name"          => "required",
            "mob"           => "required|numeric",
        ]);

        $supplier->SUPP_NAME = $request->name;
        $supplier->SUPP_MOBN = $request->mob;
        $supplier->SUPP_BLNC = $request->balance ?? 0;

        $supplies = array();
        foreach($request->raw as $i => $raw){
            $supplies[$raw] = ['SPLS_PRCE' => $request->price[$i]];
        }
        DB::transaction(function () use ($supplier, $supplies){
            $supplier->save();
            $supplier->supplies()->sync($supplies);
        });

        return redirect("suppliers/profile/" . $supplier->id);
    }
}

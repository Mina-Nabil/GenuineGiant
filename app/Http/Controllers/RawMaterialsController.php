<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use Illuminate\Http\Request;
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

    public function __construct()
    {
        $this->middleware("auth");
    }

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

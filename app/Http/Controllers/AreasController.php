<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AreasController extends Controller
{
    protected $data;
    protected $homeURL = 'areas/show';

    private function initDataArr()
    {
        $this->data['items'] = Area::all();
        $this->data['title'] = "Covered Areas";
        $this->data['subTitle'] = "Manage all Covered Areas and their delivery rate";
        $this->data['cols'] = ['Area', 'Arabic Name', 'Rate', 'Active', 'Edit'];
        $this->data['atts'] = [
            'AREA_NAME', 'AREA_ARBC_NAME', 'AREA_RATE',
            [
                'toggle' => [
                    "att"   =>  "AREA_ACTV",
                    "url"   =>  "areas/toggle/",
                    "states" => [
                        "1" => "Active",
                        "0" => "Disabled",
                    ],
                    "actions" => [
                        "1" => "disable the Area",
                        "0" => "Activate the Area",
                    ],
                    "classes" => [
                        "1" => "label-info",
                        "0" => "label-danger",
                    ],
                ]
            ],
            ['edit' => ['url' => 'areas/edit/', 'att' => 'id']],
        ];
        $this->data['homeURL'] = $this->homeURL;
    }

    public function __construct()
    {
        $this->middleware("auth");
        $this->middleware("\App\Http\Middleware\CheckType");
    }

    public function home()
    {
        $this->initDataArr();
        $this->data['formTitle'] = "Add Area";
        $this->data['formURL'] = "areas/insert";
        $this->data['isCancel'] = false;
        return view('settings.area', $this->data);
    }

    public function edit($id)
    {
        $this->initDataArr();
        $this->data['area'] = Area::findOrFail($id);
        $this->data['formTitle'] = "Edit Area ( " . $this->data['area']->AREA_NAME . " )";
        $this->data['formURL'] = "areas/update";
        $this->data['isCancel'] = false;
        return view('settings.area', $this->data);
    }

    public function toggle($id)
    {

        $area = Area::findOrfail($id);
        if ($area->AREA_ACTV) {
            $area->AREA_ACTV = 0;
        } else {
            $area->AREA_ACTV = 1;
        }
        $area->save();
        return redirect($this->homeURL);
    }

    public function insert(Request $request)
    {

        $request->validate([
            "name"      => "required|unique:areas,AREA_NAME",
            "arbcName"  => "required",
            "rate"  => "required|numeric",
        ]);

        $area = new Area();
        $area->AREA_NAME = $request->name;
        $area->AREA_ARBC_NAME = $request->arbcName;
        $area->AREA_RATE = $request->rate;
        $area->save();
        return redirect($this->homeURL);
    }

    public function update(Request $request)
    {
        $request->validate([
            "id" => "required",
        ]);
        $area = Area::findOrFail($request->id);

        $request->validate([
            "name" => ["required",  Rule::unique('areas', "AREA_NAME")->ignore($area->AREA_NAME, "AREA_NAME"),],
            "arbcName" => "required",
            "rate"  => "required|numeric",
            "id" => "required",
        ]);

        $area->AREA_NAME = $request->name;
        $area->AREA_ARBC_NAME = $request->arbcName;
        $area->AREA_RATE = $request->rate;
        $area->save();

        return redirect($this->homeURL);
    }
}

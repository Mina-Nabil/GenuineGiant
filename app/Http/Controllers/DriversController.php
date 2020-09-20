<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DriversController extends Controller
{
    protected $data;
    protected $homeURL = 'drivers/show';

    private function initDataArr()
    {
        $this->data['items'] = Driver::all();
        $this->data['title'] = "Registered Drivers";
        $this->data['subTitle'] = "Manage all Covered Drivers and their delivery rate";
        $this->data['cols'] = ['Driver', 'Mobile#', 'National ID', 'Active', 'Edit'];
        $this->data['atts'] = [
            'DRVR_NAME', 'DRVR_MOBN', 'DRVR_SRID',
            [
                'toggle' => [
                    "att"   =>  "DRVR_ACTV",
                    "url"   =>  "drivers/toggle/",
                    "states" => [
                        "1" => "Active",
                        "0" => "Disabled",
                    ],
                    "actions" => [
                        "1" => "disable the Driver",
                        "0" => "Activate the Driver",
                    ],
                    "classes" => [
                        "1" => "label-info",
                        "0" => "label-danger",
                    ],
                ]
            ],
            ['edit' => ['url' => 'drivers/edit/', 'att' => 'id']],
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
        $this->data['formTitle'] = "Add Driver";
        $this->data['formURL'] = "drivers/insert";
        $this->data['isCancel'] = false;
        return view('settings.drivers', $this->data);
    }

    public function edit($id)
    {
        $this->initDataArr();
        $this->data['driver'] = Driver::findOrFail($id);
        $this->data['formTitle'] = "Edit Driver ( " . $this->data['driver']->DRVR_NAME . " )";
        $this->data['formURL'] = "drivers/update";
        $this->data['isCancel'] = false;
        return view('settings.drivers', $this->data);
    }

    public function toggle($id)
    {

        $driver = Driver::findOrfail($id);
        if ($driver->DRVR_ACTV) {
            $driver->DRVR_ACTV = 0;
        } else {
            $driver->DRVR_ACTV = 1;
        }
        $driver->save();
        return redirect($this->homeURL);
    }

    public function insert(Request $request)
    {

        $request->validate([
            "name"  => "required|unique:drivers,DRVR_NAME",
            "mob"   =>   "required|unique:drivers,DRVR_MOBN",
            "nationalID"  => "required|numeric|unique:drivers,DRVR_MOBN",
        ]);

        $driver = new Driver();
        $driver->DRVR_NAME = $request->name;
        $driver->DRVR_MOBN = $request->mob;
        $driver->DRVR_SRID = $request->nationalID;
        $driver->save();
        return redirect($this->homeURL);
    }

    public function update(Request $request)
    {
        $request->validate([
            "id" => "required",
        ]);
        $driver = Driver::findOrFail($request->id);

        $request->validate([
            "name" => ["required",  Rule::unique('drivers', "DRVR_NAME")->ignore($driver->DRVR_NAME, "DRVR_NAME"),],
            "mob" => ["required",  Rule::unique('drivers', "DRVR_MOBN")->ignore($driver->DRVR_MOBN, "DRVR_MOBN"),],
            "nationalID"  => ["required", "numeric", Rule::unique('drivers', "DRVR_SRID")->ignore($driver->DRVR_SRID, "DRVR_SRID"),],
            "id" => "required",
        ]);

        $driver->DRVR_NAME = $request->name;
        $driver->DRVR_MOBN = $request->mob;
        $driver->DRVR_SRID = $request->nationalID;
        $driver->save();

        return redirect($this->homeURL);
    }
}

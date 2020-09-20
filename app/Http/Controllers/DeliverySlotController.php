<?php

namespace App\Http\Controllers;

use App\Models\DeliverySlot;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeliverySlotController extends Controller
{
    protected $data;
    protected $homeURL = 'slots/show';

    private function initDataArr()
    {
        $this->data['items'] = DeliverySlot::all();
        $this->data['title'] = "Delivery Slots Available";
        $this->data['subTitle'] = "Manage all Delivery Slots and their start/end times";
        $this->data['cols'] = ['Slot', 'Start Time', 'End Time','State', 'Edit'];
        $this->data['atts'] = [
            'DSLT_NAME', 
            ['date' => ['att' => 'DSLT_STRT', 'format' => 'H:i:s']], 
            ['date' => ['att' => 'DSLT_END', 'format' => 'H:i:s']],
            [
                'toggle' => [
                    "att"   =>  "DSLT_ACTV",
                    "url"   =>  "slots/toggle/",
                    "states" => [
                        "1" => "Active",
                        "0" => "Disabled",
                    ],
                    "actions" => [
                        "1" => "disable the Slot",
                        "0" => "Activate the Slot",
                    ],
                    "classes" => [
                        "1" => "label-info",
                        "0" => "label-danger",
                    ],
                ]
            ],
            ['edit' => ['url' => 'slots/edit/', 'att' => 'id']],
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
        $this->data['formTitle'] = "Add New Delivery Slot";
        $this->data['formURL'] = "slots/insert";
        $this->data['isCancel'] = false;
        return view('settings.slots', $this->data);
    }

    public function edit($id)
    {
        $this->initDataArr();
        $this->data['slot'] = DeliverySlot::findOrFail($id);
        $this->data['formTitle'] = "Edit Slot ( " . $this->data['slot']->DSLT_NAME . " )";
        $this->data['formURL'] = "slots/update";
        $this->data['isCancel'] = false;
        return view('settings.slots', $this->data);
    }

    public function toggle($id)
    {

        $slot = DeliverySlot::findOrfail($id);
        if ($slot->DSLT_ACTV) {
            $slot->DSLT_ACTV = 0;
        } else {
            $slot->DSLT_ACTV = 1;
        }
        $slot->save();
        return redirect($this->homeURL);
    }

    public function insert(Request $request)
    {

        $request->validate([
            "name"      => "required|unique:delivery_slots,DSLT_NAME",
        ]);

        $slot = new DeliverySlot();
        $slot->DSLT_NAME = $request->name;
        $slot->DSLT_STRT = $request->start;
        $slot->DSLT_END = $request->end;
        $slot->save();
        return redirect($this->homeURL);
    }

    public function update(Request $request)
    {
        $request->validate([
            "id" => "required",
        ]);
        $slot = DeliverySlot::findOrFail($request->id);

        $request->validate([
            "name" => ["required",  Rule::unique('delivery_slots', "DSLT_NAME")->ignore($slot->DSLT_NAME, "DSLT_NAME"),],
        ]);

        $slot->DSLT_NAME = $request->name;
        $slot->DSLT_STRT = $request->start;
        $slot->DSLT_END = $request->end;
        $slot->save();

        return redirect($this->homeURL);
    }
}

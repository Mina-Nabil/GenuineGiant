<?php

namespace App\Http\Controllers;

use App\Models\PaymentOption;
use Illuminate\Http\Request;

class PaymentOptionsController extends Controller
{
    protected $data;
    protected $homeURL = 'paymentoptions/show';

    public function __construct()
    {
        $this->middleware("auth");
        $this->middleware("\App\Http\Middleware\CheckType");
    }

    private function initDataArr()
    {
        $this->data['items'] = PaymentOption::all();
        $this->data['title'] = "Payment Options Available";
        $this->data['subTitle'] = "Manage Payment Options - disable/enable Options";
        $this->data['cols'] = ['Name', 'Arabic Name', 'Active'];
        $this->data['atts'] = [
            'PYOP_NAME', 'PYOP_ARBC_NAME',
            [
                'toggle' => [
                    "att"   =>  "PYOP_ACTV",
                    "url"   =>  "paymentoptions/toggle/",
                    "states" => [
                        "1" => "Active",
                        "0" => "Disabled",
                    ],
                    "actions" => [
                        "1" => "disable the Option",
                        "0" => "Activate the Option",
                    ],
                    "classes" => [
                        "1" => "label-info",
                        "0" => "label-danger",
                    ],
                ]
            ]
        ];
        $this->data['homeURL'] = $this->homeURL;
    }

    public function home()
    {
        $this->initDataArr();
        return view('settings.paymentoptions', $this->data);
    }

    public function toggle($id)
    {

        $paymentOption = PaymentOption::findOrfail($id);
        if ($paymentOption->PYOP_ACTV) {
            $paymentOption->PYOP_ACTV = 0;
        } else {
            $paymentOption->PYOP_ACTV = 1;
        }
        $paymentOption->save();
        return redirect($this->homeURL);
    }
}

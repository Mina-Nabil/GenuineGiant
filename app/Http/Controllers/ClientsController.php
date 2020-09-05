<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Gender;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ClientsController extends Controller
{
    protected $data;
    protected $homeURL = "clients/show/all";

    public function __construct()
    {
        $this->middleware("auth");
    }

    private function initHomeArr($type = -1) // 0 all - 1 latest - 2 top
    {

        $this->data['title'] = "All Registered Clients";
        $this->data['type'] = $type;
        if ($type == 1)
            $this->data['items'] = Client::latest()->limit(100)->get();
        else
            $this->data['items'] = Client::all()->sortByDesc('id');

        $this->data['subTitle'] = "Manage Clients";
        $this->data['cols'] = ['id', 'Full Name', 'Mob#', 'Balance', 'Area', 'Since', 'Edit'];
        $this->data['atts'] = [
            'id',
            ['attUrl' => ["url" => 'clients/profile', "urlAtt" => 'id', "shownAtt" =>  "CLNT_NAME"]],
            'CLNT_MOB',
            ['number' => ['att' => 'CLNT_BLNC']],
            ['foreign' => ['area', 'AREA_NAME']],
            // ['sumForeign' => ['rel' => 'orders', 'att' => 'ORDR_TOTL']],
            ['date' => ['att' => 'created_at', 'format' => 'Y-M-d']],
            ['edit' => ['url' => 'clients/edit/', 'att' => 'id']]
        ];
        $this->data['homeURL'] = $this->homeURL;
    }

    private function initAddArr($clientID = -1)
    {
        if ($clientID != -1) {
            $this->data['client'] = Client::findOrFail($clientID);
            $this->data['formURL'] = "clients/update";
        } else {
            $this->data['formURL'] = "clients/insert/";
        }
        $this->data['areas']  = Area::where("AREA_ACTV", "1")->get();
        $this->data['formTitle'] = "Add New Client";
        $this->data['isCancel'] = true;
        $this->data['homeURL'] = $this->homeURL;
    }

    private function initProfileArr($id)
    {
        $this->data['client'] = Client::findOrFail($id);
        $this->data['clientMoney'] = $this->data['client']->moneyPaid();
        $this->data['formURL'] = "clients/update";
        $this->data['areas']  = Area::where("AREA_ACTV", "1")->get();

        //Orders Array
        $this->data['orderList']    = Order::getOrdersByClient($id);
        $this->data['cardTitle'] = false;
        $this->data['ordersCols'] = ['id', 'Status', 'Payment',  'Items', 'Ordered On', 'Total'];
        $this->data['orderAtts'] = [
            ['attUrl' => ['url' => "orders/details", "shownAtt" => 'id', "urlAtt" => 'id']],
            [
                'stateQuery' => [
                    "classes" => [
                        "1" => "label-info",
                        "2" => "label-warning",
                        "3" =>  "label-dark bg-dark",
                        "4" =>  "label-success",
                        "5" =>  "label-danger",
                    ],
                    "att"           =>  "ORDR_STTS_ID",
                    'foreignAtt'    => "STTS_NAME",
                    'url'           => "orders/details/",
                    'urlAtt'        =>  'id'
                ]
            ],
            'PYOP_NAME',
            'itemsCount',
            'ORDR_OPEN_DATE',
            'ORDR_TOTL'
        ];

        //Items Bought
        $this->data['boughtList'] = $this->data['client']->itemsBought();
        $this->data['boughtCols'] = ['Model', 'Color', 'Size', 'Count'];
        $this->data['boughtAtts'] = [
            'PROD_NAME',
            'COLR_NAME',
            'SIZE_NAME',
            'itemCount'
        ];
    }

    public function home()
    {
        $this->initHomeArr(0);
        return view("clients.table", $this->data);
    }

    public function latest()
    {
        $this->initHomeArr(1);
        return view("clients.table", $this->data);
    }

    public function top()
    {
        $this->initHomeArr(2);
        return view("clients.table", $this->data);
    }

    public function add()
    {
        $this->initAddArr();
        return view("clients.add", $this->data);
    }

    public function edit($id)
    {
        $this->initAddArr($id);
        return view("clients.add", $this->data);
    }

    public function profile($id)
    {
        $this->initProfileArr($id);
        return view("clients.profile", $this->data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            "name"              => "required",
            "mob"               => "required|numeric",
            "area"          => "required|exists:areas,id"
        ]);

        $client = new Client();
        $client->CLNT_NAME = $request->name;
        $client->CLNT_ADRS = $request->address;
        $client->CLNT_MOBN = $request->mob;
        $client->CLNT_BLNC = $request->balance ?? 0;
        $client->CLNT_AREA_ID = $request->area;

        $client->save();

        return redirect("clients/profile/" . $client->id);
    }

    public function update(Request $request)
    {
        $request->validate([
            "id"          => "required",
        ]);
        $client = Client::findOrFail($request->id);
        $request->validate([
            "name"          => "required",
            "mob"           => "required|numeric",
            "area"          => "required|exists:areas,id"
        ]);

        $client->CLNT_NAME = $request->name;
        $client->CLNT_ADRS = $request->address;
        $client->CLNT_MOBN = $request->mob;
        $client->CLNT_BLNC = $request->balance ?? 0;
        $client->CLNT_AREA_ID = $request->area;

        $client->save();

        return redirect("clients/profile/" . $client->id);
    }
}

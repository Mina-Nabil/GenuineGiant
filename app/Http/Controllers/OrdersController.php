<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Driver;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentOption;
use App\Models\Product;
use App\Models\Client;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public $data;
    public $homeURL = "orders/acrive";

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware("\App\Http\Middleware\CheckType");
    }

    public function active()
    {
        $this->initTableArr(1);
        $this->data['newCount'] = Order::getOrdersCountByState(1);
        $this->data['readyCount'] = Order::getOrdersCountByState(2);
        $this->data['inDeliveryCount'] = Order::getOrdersCountByState(3);
        return view("orders.active", $this->data);
    }
    public function state(int $stateID)
    {
        $this->initTableArr(false, $stateID);
        if ($stateID > 0 && $stateID < 4) {
            $this->data['newCount'] = Order::getOrdersCountByState(1);
            $this->data['readyCount'] = Order::getOrdersCountByState(2);
            $this->data['inDeliveryCount'] = Order::getOrdersCountByState(3);
        } elseif ($stateID > 3 && $stateID < 7) {
            $this->data['deliveredCount'] = Order::getOrdersCountByState(4);
            $this->data['cancelledCount'] = Order::getOrdersCountByState(5);
            $this->data['returnedCount'] = Order::getOrdersCountByState(6);
            return view("orders.history", $this->data);
        } else {
            abort(404);
        }
        return view("orders.active", $this->data);
    }

    public function monthly(int $stateID = -1)
    {
        $this->initTableArr(false, $stateID, date('m'), date('Y'));
        $startDate  = $this->getStartDate(date('m'), date('Y'));
        $endDate    = $this->getEndDate(date('m'), date('Y'));
        $this->data['deliveredCount'] = Order::getOrdersCountByState(4, $startDate, $endDate);
        $this->data['cancelledCount'] = Order::getOrdersCountByState(5, $startDate, $endDate);
        $this->data['returnedCount'] = Order::getOrdersCountByState(6, $startDate, $endDate);
        $this->data['historyURL'] = "orders/month";
        return view("orders.history", $this->data);
    }

    public function loadHistory()
    {
        $data['years'] = Order::selectRaw('YEAR(ORDR_OPEN_DATE) as order_year')->distinct()->get();
        return view("orders.prepareHistory", $data);
    }

    public function history($year, $month, $state = -1)
    {
        $this->initTableArr(false, $state, $month, $year);
        $startDate  = $this->getStartDate($month, $year);
        $endDate    = $this->getEndDate($month, $year);
        $this->data['historyURL'] = url('orders/history/' . $year . '/' . $month);
        $this->data['deliveredCount'] = Order::getOrdersCountByState(4, $startDate, $endDate);
        $this->data['cancelledCount'] = Order::getOrdersCountByState(5, $startDate, $endDate);
        $this->data['returnedCount'] = Order::getOrdersCountByState(6, $startDate, $endDate);
        return view("orders.history", $this->data);
    }

    public function addNew()
    {
        $this->data['inventory']    =   Inventory::with("product")->get();
        $this->data['areas']        =   Area::where('AREA_ACTV', 1)->get();
        $this->data['clients']        =   Client::all();
        $this->data['payOptions']   =  PaymentOption::where('PYOP_ACTV', 1)->get();
        $this->data['formTitle'] = "Add New Order";
        $this->data['formURL'] = "orders/insert";
        $this->data['isCancel'] = true;
        $this->data['homeURL']  = $this->homeURL;

        return view("orders.add", $this->data);
    }
    //////////////////////////////Order Details Page and Functions//////////////////////////////////////////
    public function details($id)
    {
        $data = Order::getOrderDetails($id); //returns order Array and Items Array

        //Status Panel
        $data['isOrderReady'] = true;
        foreach ($data['items'] as $item)
            if (!$item->ORIT_VRFD) {
                $data['isOrderReady'] = false;
                break;
            }
        $data['isPartiallyReturned']    =   (($data['order']->ORDR_STTS_ID == 4 || $data['order']->ORDR_STTS_ID == 3) && isset($data['order']->ORDR_RTRN_ID) && is_numeric($data['order']->ORDR_RTRN_ID));
        $data['isFullyReturned']        =   ($data['order']->ORDR_STTS_ID == 6);
        $data['isCancelled']        =   ($data['order']->ORDR_STTS_ID == 5);

        $data['setOrderNewUrl']             =   url('orders/set/new/' . $data['order']->id);
        $data['setOrderReadyUrl']           =   url('orders/set/ready/' . $data['order']->id);
        $data['setOrderInDeliveryUrl']      =   url('orders/set/indelivery/' . $data['order']->id);
        $data['setOrderCancelledUrl']       =   url('orders/set/cancelled/' . $data['order']->id);
        $data['setOrderDeliveredUrl']       =   url('orders/set/delivered/' . $data['order']->id);
        $data['linkNewReturnUrl']           =   url('orders/create/return/' . $data['order']->id);
        $data['settleOrderOnBalance']           =   url('orders/settle/payment/' . $data['order']->id);
        $data['returnUrl']                  =   url('orders/return/' . $data['order']->id);

        //Add Items Panel
        $data['inventory']      =   Inventory::with("product")->get();
        $data['isCancel']       =   false;
        $data['addFormURL']     =   url('orders/add/items/' . $id);

        //Driver Panel
        $data['drivers']      =   Driver::all();
        $data['assignDriverFormURL']     =   url('orders/assign/driver');

        //Payment Panel
        $data['paymentURL']             =   url('orders/collect/payment');
        $data['deliveryPaymentURL']     =   url('orders/collect/delivery');
        $data['discountURL']            =   url('orders/set/discount');

        //Edit Info Panel
        $data['areas']                  = Area::where('AREA_ACTV', 1)->get();
        $data['editInfoURL']             =   url('orders/edit/details');

        $data['remainingMoney']         =   $data['order']->ORDR_TOTL - $data['order']->ORDR_PAID - $data['order']->ORDR_DISC - $data['order']->ORDR_CLNT_BLNC;

        return view("orders.details", $data);
    }

    public function insertNewItems($orderID, Request $request)
    {
        $order = Order::findOrFail($orderID);
        DB::transaction(function () use ($order, $request) {
            $orderItemArray = $this->getOrderItemsArray($request);
            foreach ($orderItemArray as $item) {
                $orderItem = $order->order_items()->firstOrNew(
                    ['ORIT_INVT_ID' => $item['ORIT_INVT_ID']]
                );
                $orderItem->ORIT_KGS    += $item['ORIT_KGS'];
                $orderItem->ORIT_PRCE   = $item['ORIT_PRCE'];
                $orderItem->ORIT_VRFD   = 0;
                $orderItem->save();
            }
            $order->recalculateTotal();
            $order->addTimeline("New Items added to Order");
        });
        return redirect("orders/details/" . $order->id);
    }

    public function setReady($id)
    {

        $order = Order::findOrFail($id);
        DB::transaction(function () use ($order) {
            $isReady = true;
            foreach ($order->order_items as $item) {
                if ($item->ORIT_VRFD == '0') {
                    $isReady = false;
                    break;
                }
            }
            if ($isReady) {
                $order->ORDR_STTS_ID = 2;
                $order->save();
                $order->addTimeline("Order set as Ready");
            }
        });
        return redirect("orders/details/" . $order->id);
    }

    public function setCancelled($id)
    {

        $order = Order::findOrFail($id);
        DB::transaction(function () use ($order) {
            $isReturned = Inventory::returnOrderItems($order);

            // foreach ($order->order_items as $item) {
            //     $inventory = Inventory::findOrfail($item->ORIT_INVT_ID);
            //     $inventory->INVT_KGS += $item->ORIT_KGS;
            //     if (!$inventory->save()) {
            //         $isReturned = false;
            //         break;
            //     }
            // }
            if ($isReturned) {
                $order->ORDR_STTS_ID = 5;
                $order->ORDR_PAID = 0;
                $order->ORDR_DLVR_DATE = date('Y-m-d H:i:s');
                $order->ORDR_DRVR_ID = null;
                $order->save();
                $order->addTimeline("Order cancelled :(");
            }
        });
        return redirect("orders/details/" . $order->id);
    }

    public function setNew($id)
    {

        $order = Order::findOrFail($id);
        $order->ORDR_STTS_ID = 1;
        $order->save();
        return redirect("orders/details/" . $order->id);
    }

    public function setInDelivery($id)
    {
        $order = Order::findOrFail($id);
        DB::transaction(function () use ($order) {
            if ($order->ORDR_STTS_ID == 2 && isset($order->driver) && $order->driver->DRVR_ACTV) {
                $order->ORDR_STTS_ID = 3;
                $order->save();
            }
            $order->addTimeline("Order set as in delivery");
        });
        return redirect("orders/details/" . $order->id);
    }

    public function setDelivered($id)
    {
        $order = Order::findOrFail($id);
        DB::transaction(function () use ($order) {
            $remainingMoney = $order->ORDR_TOTL - $order->ORDR_DISC - $order->ORDR_PAID - $order->ORDR_CLNT_BLNC;
            if ($order->ORDR_STTS_ID == 3 && $remainingMoney == 0) {
                $order->ORDR_STTS_ID = 4;
                $order->ORDR_DLVR_DATE = date('Y-m-d H:i:s');
                $order->save();
            }
            $order->addTimeline("Order delivered :)");
        });
        return redirect("orders/details/" . $order->id);
    }

    public function setFullyReturned($id)
    {
        $order = Order::findOrFail($id);
        DB::transaction(function () use ($order) {
            $isReturned = Inventory::returnOrderItems($order);;
            // foreach ($order->order_items as $item) {
            //     $inventory = Inventory::findOrfail($item->ORIT_INVT_ID);
            //     $inventory->INVT_KGS += $item->ORIT_KGS;
            //     if (!$inventory->save()) {
            //         $isReturned = false;
            //         break;
            //     }
            // }
            if ($isReturned) {
                $order->ORDR_STTS_ID = 6;
                $order->ORDR_PAID = 0;
                $order->ORDR_DLVR_DATE = date('Y-m-d H:i:s');
                $order->save();
                $order->addTimeline("Order fully returned :(");
            }
        });
    }

    public function setPartiallyReturned($id)
    {
        //This function will create new return order 
        $order = Order::findOrFail($id);
        $retOrder = new Order();
        DB::transaction(function () use ($order, $retOrder) {
            if (isset($order->ORDR_CLNT_ID))
                $retOrder->ORDR_CLNT_ID = $order->ORDR_CLNT_ID;
            else {
                $retOrder->ORDR_GEST_NAME = $order->ORDR_GEST_NAME;
                $retOrder->ORDR_GEST_MOBN = $order->ORDR_GEST_MOBN;
            }
            $retOrder->ORDR_OPEN_DATE = date('Y-m-d H:i:s');
            $retOrder->ORDR_DLVR_DATE =  $retOrder->ORDR_OPEN_DATE;
            $retOrder->ORDR_ADRS = $order->ORDR_ADRS;
            $retOrder->ORDR_NOTE = "New Return Order for order number " . $order->id;
            $retOrder->ORDR_AREA_ID = $order->ORDR_AREA_ID;
            $retOrder->ORDR_PYOP_ID = $order->ORDR_PYOP_ID;
            $retOrder->ORDR_STTS_ID = 6; // new returned order
            $retOrder->ORDR_DASH_ID = Auth::user()->id; // new return order
            $retOrder->ORDR_TOTL = 0;
            $retOrder->save();
            $order->ORDR_RTRN_ID = $retOrder->id; // new returned order
            $order->save();
            $order->addTimeline("New Return Order opened");
        });
        return redirect("orders/details/" . $order->id);
    }

    public function assignDriver(Request $request)
    {
        $request->validate([
            'id' => "required",
            'driver' => "required|exists:drivers,id"
        ]);

        $order = Order::findOrFail($request->id);
        DB::transaction(function () use ($request, $order) {
            if ($order->ORDR_STTS_ID < 3) { // New or ready
                $order->ORDR_DRVR_ID = $request->driver;
                $order->save();
            }
            $driver = Driver::findOrFail($request->driver);
            $order->addTimeline($driver->DRVR_NAME . " assigned as the order delivery man");
        });
        return redirect("orders/details/" . $order->id);
    }

    public function collectNormalPayment(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $request->validate([
            'id' => "required",
            'payment' => "required|min:0|max:" . $order->ORDR_TOTL
        ]);
        DB::transaction(function () use ($request, $order) {
            if ($order->ORDR_STTS_ID < 4) {
                $order->ORDR_PAID += $request->payment;
                $order->save();
            }
            $order->addTimeline($request->payment . "EGP collected as Normal Order payment");
        });

        return redirect("orders/details/" . $order->id);
    }

    public function collectDeliveryPayment(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $request->validate([
            'id' => "required",
            'deliveryPaid' => "required|min:0"
        ]);
        DB::transaction(function () use ($request, $order) {
            $order->ORDR_DLFE = $request->deliveryPaid;
            $order->save();
            $order->addTimeline($request->deliveryPaid . "EGP collected as Delivery payment");
        });
        return redirect("orders/details/" . $order->id);
    }

    public function setDiscount(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $request->validate([
            'id' => "required",
            'discount' => "required|min:0|max:" . $order->ORDR_TOTL
        ]);
        DB::transaction(function () use ($request, $order) {
            if ($order->ORDR_STTS_ID < 4) {
                $order->ORDR_DISC = $request->discount;
                $order->save();
            }
            $order->addTimeline("Discount added on order, discount now is set to " . $order->ORDR_DISC);
        });

        return redirect("orders/details/" . $order->id);
    }

    public function settleFromClientBalance($id){
        $order = Order::findOrFail($id); 
        if(!(isset($order->ORDR_CLNT_ID) || is_numeric($order->ORDR_CLNT_ID))) {
            abort(404);
        }
        $remainingMoney = $order->ORDR_TOTL - $order->ORDR_PAID - $order->ORDR_DISC - $order->ORDR_CLNT_BLNC;
        $client = Client::findOrFail($order->ORDR_CLNT_ID);
        DB::transaction(function () use ($client, $order, $remainingMoney){
            $client->pay(-1*$remainingMoney, "Order Settlement", $order->id);
            $order->ORDR_CLNT_BLNC += $remainingMoney;
            $order->save();
        });
        return redirect("orders/details/" . $order->id);
    }

    public function toggleItem($id)
    {

        $item = OrderItem::findOrfail($id);
        $order = Order::findOrfail($item->ORIT_ORDR_ID);

        try {
            DB::transaction(function () use ($item, $order) {

                if ($order->ORDR_STTS_ID != 1) { //still new
                    return 'failed';
                }

                $product = Product::findOrFail($item->inventory->INVT_PROD_ID);

                if ($item->ORIT_VRFD) {
                    $item->ORIT_VRFD = 0;
                    $debug = Inventory::insertEntry(array([
                        "modelID"   =>  $product->id,
                        "count"     =>  $item->ORIT_KGS,
                    ]), $item->ORIT_ORDR_ID, true, "Order (" . $order->id . ") Item unready"); //added from order

                    $order->addTimeline("Item (" . $product->PROD_NAME . ") set as unready");
                } else {
                    $item->ORIT_VRFD = 1;
                    Inventory::insertEntry(array([
                        "modelID"   =>  $product->id,
                        "count"     =>  $item->ORIT_KGS,
                    ]), $item->ORIT_ORDR_ID, false, "Order (" . $order->id . ") Item ready");
                    $order->addTimeline("Item (" . $product->PROD_NAME . ") set as ready");
                }
                $item->save();

                return 1;
            });
        } catch (Exception $e) {
            return 'failed';
        }

        return 1;
    }

    public function deleteItem($id)
    {

        $item = OrderItem::findOrfail($id);
        $order = Order::findOrfail($item->ORIT_ORDR_ID);
        DB::transaction(function () use ($order, $item) {
            if ($order->ORDR_STTS_ID != 1) { //still new
                return 'failed';
            }
            $product = Product::findOrFail($item->inventory->INVT_PROD_ID);
            if ($item->ORIT_VRFD == 1) {

                Inventory::insertEntry(array([
                    "modelID"   =>  $product->id,
                    "count"     =>  $item->ORIT_KGS,
                ]), $item->ORIT_ORDR_ID, true, "Order (" . $order->id . ") Item deleted");
            }
            $item->delete();
            $order->recalculateTotal();
            $order->addTimeline("Item (" . $product->PROD_NAME . ") deleted by dashboard user");
        });
        return redirect("orders/details/" . $order->id);
    }

    public function changeQuantity(Request $request)
    {
        $request->validate([
            "itemID" => "required",
            "count" => "required|numeric|min:0"
        ]);
        $orderItem = OrderItem::findOrFail($request->itemID);
        $order = Order::findOrfail($orderItem->ORIT_ORDR_ID);
        DB::transaction(function () use ($order, $orderItem, $request) {
            if (($order->ORDR_STTS_ID == 4 || $order->ORDR_STTS_ID == 3) && isset($order->ORDR_RTRN_ID) && is_numeric($order->ORDR_RTRN_ID)) {
                //if and in delivery or delivered and has a returned order

                //create new return item and add count to return item
                $returnOrder = Order::findOrFail($order->ORDR_RTRN_ID);
                $returnedItem = $returnOrder->order_items()->firstOrNew([
                    'ORIT_INVT_ID' => $orderItem->ORIT_INVT_ID
                ]);
                $returnedItem->ORIT_KGS += $request->count;
                $returnedItem->ORIT_PRCE = $request->price;
                $returnedItem->ORIT_VRFD = 1;
                $returnedItem->save();
                $returnOrder->recalculateTotal();

                //Adjust inventory
                $product = Product::findOrFail($returnedItem->inventory->INVT_PROD_ID);
                Inventory::insertEntry(array([
                    "modelID"   =>  $product->id,
                    "count"     =>  $returnedItem->ORIT_KGS,
                ]), $returnedItem->ORIT_ORDR_ID, true, "Order (" . $order->id . ") Item returned");

                //Adjust old order
                $orderItem->ORIT_KGS -= $request->count;
                if ($orderItem->ORIT_KGS <= 0)
                    $orderItem->delete();
                else
                    $orderItem->save();
                $order->recalculateTotal();
                $order->addTimeline("Item (" . $product->PROD_NAME . ") added to Return Order");
            } elseif ($order->ORDR_STTS_ID != 1) { //if it is not still new
                return redirect("orders/details/" . $orderItem->ORIT_ORDR_ID);
            } else {
                //check if it is verified
                if ($orderItem->ORIT_VRFD = 0) {
                    $product = Product::findOrFail($orderItem->inventory->INVT_PROD_ID);
                    //if yes return items in inventory as it will be re added
                    Inventory::insertEntry(array([
                        "modelID"   =>  $product->id,
                        "count"     =>  $orderItem->ORIT_KGS,
                    ]), $orderItem->ORIT_ORDR_ID, true, "Order (" . $order->id . ") Item (" . $product->PROD_NAME . ") Quantity changed");
                }
                $orderItem->ORIT_KGS = $request->count;
                $orderItem->ORIT_PRCE = $request->price;
                $orderItem->ORIT_VRFD = 0;
                $orderItem->save();
                $order->recalculateTotal();
                $order->addTimeline("Item quantity changed by dashboard user");
            }
        });
        return redirect("orders/details/" . $orderItem->ORIT_ORDR_ID);
    }

    public function editOrderInfo(Request $request)
    {
        $request->validate([
            "id" => "required",

        ]);
        $order = Order::findOrfail($request->id);
        DB::transaction(function () use ($request, $order) {
            $order->ORDR_ADRS = $request->address;
            $order->ORDR_NOTE = $request->note;
            $order->ORDR_AREA_ID = $request->area;
            $order->save();
            $order->addTimeline("Order edited by dashboard user");
        });
        return redirect("orders/details/" . $order->id);
    }

    ////////////////////////////Insert Order from dashboard///////////////////////////

    public function insert(Request $request)
    {

        $request->validate([
            "client"          =>  "required_if:guest,2|nullable|exists:clients,id",
            "guestName"     =>  "required_if:guest,1",
            "guestMob"      =>  "required_if:guest,1",
            "area"          =>  "required",
            "option"        =>  "required",
            "address"      =>  "required"
        ]);
        $order = new Order();
        DB::transaction(function () use ($request, $order) {
            if (isset($request->client))
                $order->ORDR_CLNT_ID = $request->client;
            else {
                $order->ORDR_GEST_NAME = $request->guestName;
                $order->ORDR_GEST_MOBN = $request->guestMob;
            }
            $order->ORDR_OPEN_DATE = date('Y-m-d H:i:s');
            $order->ORDR_ADRS = $request->address;
            $order->ORDR_NOTE = $request->note;
            $order->ORDR_AREA_ID = $request->area;
            $order->ORDR_PYOP_ID = $request->option;
            $order->ORDR_STTS_ID = 1; // new order
            $order->ORDR_DASH_ID = Auth::user()->id; // new order

            $orderItemArray = $this->getOrderItemsObjectArray($request);

            $order->ORDR_TOTL = $this->getOrderTotal($request);

            $order->save();
            $order->addTimeline("Order Opened by dashboard user");
            $order->order_items()->saveMany($orderItemArray);
        });

        return redirect("orders/details/" . $order->id);
    }


    /***
     * 
     * @param $isActive int
     * if active = 1 , history = 2
     * @param $state
     * 1 New - 2 Ready - 3 In Delivery - 4 Delivered - 5 Cancelled - 6 Returned
     * @param $year int
     * if history set year e.g 2020
     * 
     */
    private function initTableArr($isActive, $state = -1, $month = -1, $year = -1)
    {
        if ($isActive == 1)
            $this->data['items']    = Order::getActiveOrders();
        elseif ($month == -1 && $year == -1) {
            $this->data['items']    = Order::getActiveOrders($state);
        } else {
            $this->data['items']    = Order::getOrdersByDate(false, $month, $year, $state);
        }
        $this->data['cardTitle'] = true;
        $this->data['cols'] = ['id', 'Client', 'Status', 'Area', 'Payment', 'Ordered On', 'Closed On',  'KGs', 'Total'];
        $this->data['atts'] = [
            ['attUrl' => ['url' => "orders/details", "shownAtt" => 'id', "urlAtt" => 'id']],
            ['urlOrStatic' => ['url' => "clients/profile", "shownAtt" => 'CLNT_NAME', "urlAtt" => 'ORDR_CLNT_ID', 'static' => 'ORDR_GEST_NAME']],
            [
                'stateQuery' => [
                    "classes" => [
                        "1" => "label-info",
                        "2" => "label-warning",
                        "3" =>  "label-dark bg-dark",
                        "4" =>  "label-success",
                        "5" =>  "label-danger",
                        "6" =>  "label-primary",
                    ],
                    "att"           =>  "ORDR_STTS_ID",
                    'foreignAtt'    => "STTS_NAME",
                    'url'           => "orders/details/",
                    'urlAtt'        =>  'id'
                ]
            ],
            'AREA_NAME',
            'PYOP_NAME',
            'ORDR_OPEN_DATE',
            'ORDR_DLVR_DATE',
            ['number' => ['att' => 'itemsCount']],
            ['number' => ['att' => 'ORDR_TOTL']]
        ];
    }

    private function getOrderItemsArray(Request $request)
    {
        $retArr = array();
        foreach ($request->item as $index => $item) {
            array_push(
                $retArr,
                ["ORIT_INVT_ID" => $item, "ORIT_KGS" => $request->count[$index], "ORIT_PRCE" => $request->price[$index]]
            );
        }
        return $retArr;
    }

    private function getOrderItemsObjectArray(Request $request)
    {
        $retArr = array();
        foreach ($request->item as $index => $item) {
            array_push($retArr, new OrderItem(
                ["ORIT_INVT_ID" => $item, "ORIT_KGS" => $request->count[$index], "ORIT_PRCE" => $request->price[$index]]
            ));
        }
        return $retArr;
    }

    private function getOrderTotal(Request $request)
    {
        $total = 0;
        foreach ($request->item as $index => $item) {
            $price = $request->price[$index] * $request->count[$index];
            $total += $price;
        }
        return $total;
    }

    ///////////////Helper Functions
    private function getStartDate($month, $year)
    {
        $retDate = null;
        if ($month == -1) {
            $retDate = $year . '-01-01 00:00:00';
        } else {
            $retDate = $year . '-' . $month . '-01 00:00:00';
        }
        return $retDate;
    }

    private function getEndDate($month, $year)
    {
        $retDate = null;
        if ($month == -1) {
            $retDate = $year . '-12-31 23:59:59';
        } else {
            $retDate = (new DateTime($year . '-' . $month . '-01'))->format('Y-m-t 23:59:59');
        }
        return $retDate;
    }
}

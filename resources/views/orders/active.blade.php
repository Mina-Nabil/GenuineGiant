@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="row ">
            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <div class="card">
                    <a href="{{url('orders/state/1')}}">
                        <div class="box bg-info text-center">
                            <h1 class="font-light text-white">{{$newCount}}</h1>
                            <h6 class="text-white">New</h6>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <div class="card">
                    <a href="{{url('orders/state/2')}}">
                        <div class="box bg-warning text-center">
                            <h1 class="font-light text-white">{{$readyCount}}</h1>
                            <h6 class="text-white">Ready</h6>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <div class="card">
                    <a href="{{url('orders/state/3')}}">
                        <div class="box bg-dark text-center">
                            <h1 class="font-dark text-light">{{$inDeliveryCount}}</h1>
                            <h6 class="text-white">In Delivery</h6>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Column -->
        </div>
    </div>

    <div class="col-12">


        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Home Page</h4>
                <h6 class="card-subtitle">Add, manage daily orders</h6>

                <div class="table-responsive m-t-5">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-success-table hover-table" data-display-length='-1' data-order="[]">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Status</th>
                                <th>Area</th>
                                <th>Driver</th>
                                <th>Closed</th>
                                <th>KGs</th>
                                <th>Total</th>
                                <th>Collected</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{  $order->ORDR_OPEN_DATE  }} {{$order->DSLT_NAME}}</td>
                                <td>
                                    <a href="{{ url('clients/profile/' . $order->ORDR_CLNT_ID) }}">{{  $order->CLNT_NAME  }} </a>
                                        @if($order->CLNT_BLNC != 0)
                                        ({{$order->CLNT_BLNC}})
                                        @endif
                                   
                                </td>
                                <td>
                                    <a href="{{ url('orders/details/' . $order->id)}}" target="_blank">
                                        <button class="label {{ $classes[$order->ORDR_STTS_ID] }}">{{ $order->STTS_NAME  }}</button>
                                    </a>
                                </td>
                                <td>{{$order->AREA_NAME}}</td>
                                <td>{{$order->DRVR_NAME}}</td>
                                <td>{{  $order->ORDR_DLVR_DATE  }}</td>
                                <td id="orderCount{{$order->id}}">{{  $order->itemsCount  }}</td>
                                <td id="orderTotal{{$order->id}}">{{  $order->ORDR_TOTL  }}</td>
                                <td id="orderPaid{{$order->id}}">{{  $order->ORDR_PAID }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button style="padding:.1rem .2rem" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">Action</button>

                                        <div class="dropdown-menu">
                                            <button class="dropdown-item open-showDetails" data-toggle="modal" data-id="{{$order->id}}" data-target="#addItems">Order details</button>
                                            @if($order->ORDR_STTS_ID == 1)
                                            <button class="dropdown-item open-addItems" data-toggle="modal" data-id="{{$order->id}}" data-target="#addItems">Add Items</button>
                                            <button class="dropdown-item" onclick="setReady($order->id)">Set as Ready</button>
                                            @endif
                                            <button class="dropdown-item open-addItems" data-toggle="modal" data-id="{{$order->id}}" data-target="#editItems">Check Items</button>
                                            @if($order->ORDR_STTS_ID < 3)
                                            <button class="dropdown-item open-setDriver" data-toggle="modal" data-id="{{$order->id}}" data-target="#setDriver">Set Driver</button>
                                            @endif
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="addItems" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Order Items</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <div class="modal-body">
                <div class="row ">
                    <div id="dynamicContainer" class="nopadding row col-lg-12">
                    </div>
                    <input id="orderIDField" type="hidden">

                    <div class="row col-lg-12">
                        <div class="col-lg-6">
                            <div class="input-group mb-2">
                                <select name=item[] class="form-control select2  custom-select" style="width:100%" id=inventorySel1 onchange="changePrices(inventorySel1)" required>
                                    <option disabled hidden selected value="">Model</option>
                                    @foreach($inventory as $item)
                                    <option value="{{ $item->id }}">
                                        {{$item->product->PROD_NAME}} - {{$item->product->PROD_ARBC_NAME}} : Available: {{number_format($item->INVT_KGS,2)}}KGs</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="input-group mb-3">
                                <input list=as3ar1 id=price1 type="number" step="0.01" class="form-control amount" placeholder="Price" name=price[] aria-describedby="basic-addon11" required>
                                <datalist id=as3ar1></datalist>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group mb-3">
                                <input type="number" step=0.01 id=count1 class="form-control amount" placeholder="KGs" min=0 name=count[] aria-describedby="basic-addon11" required>
                                <div class="input-group-append">
                                    <button class="btn btn-success" id="dynamicAddButton" type="button" onclick="addToab();"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=row>
                    <div class="col-2 m-r-10">
                        <button type="button" onclick="addItem()" class="btn btn-success">Submit</button>
                    </div>

                    <div class="col-2 m-r-10">
                        <button type="button" style="display: none" id="deleteBankButton" class="btn btn-danger mr-2">Delete</button>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_content')
<script src="{{ asset('assets/node_modules/toast-master/js/jquery.toast.js') }}"></script>
<script>
    function loadItems(orderID){

    }

    function addItem(){
        var http = new XMLHttpRequest();
        var url = "{{url('api/add/order/items')}}";
        http.open('POST', url, true);

        var formdata = new FormData();
        var orderID = $(".modal-body #orderIDField").val()
        formdata.append('orderID', orderID);
        formdata.append('_token','{{ csrf_token() }}');
        var items = document.querySelectorAll("[id^='inventorySel']");
        console.log(items)
        for( var i = 0; i < items.length; i++ )    
            formdata.append(items[i].name, items[i].value);
        var prices = document.querySelectorAll('input[name*=price]');
   
        for( var i = 0; i < prices.length; i++ )    
            formdata.append(prices[i].name, prices[i].value);
        var counts = document.querySelectorAll('input[name*=count]');

        for( var i = 0; i < counts.length; i++ )    
            formdata.append(counts[i].name, counts[i].value);

        http.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                try {        
                    res = JSON.parse(this.responseText);
                    console.log(orderID)
                    $('#orderCount' + orderID).html(res.count)
                    $('#orderTotal' + orderID).html(res.total)
                    addModalReset()
                    $.toast({
                    heading: 'Items Added',
                    text: 'Items Added Successfully. New Order total is ' + res.total ,
                    position: 'top-right',
                    loaderBg:'#ff6849',
                    icon: 'success',
                    hideAfter: 3000, 
                    stack: 6
                 });
                } catch(e){
                    console.log(e); 
                }
            } 
        };
        http.send(formdata, true);
    }

    function addModalReset(){
        $("[id^=inventorySel]").select2("val", "")
        $("[id^=count]").val("")
        $("[id^=price]").val("")
    }

    function changePrices(callerID) {
    itemIndex = callerID.id.substring(12, callerID.id.length);
    prodID = callerID.options[callerID.selectedIndex].value;


    var http = new XMLHttpRequest();
    var url = "{{url('api/get/product/prices')}}";
    http.open('POST', url, true);
    //Send the proper header information along with the request
    //http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    var formdata = new FormData();
    formdata.append('id',prodID);
    formdata.append('_token','{{ csrf_token() }}');

    http.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        try {        
            prices = JSON.parse(this.responseText);
            priceText = document.getElementById('price' + itemIndex);
            priceText.value = '';
            listtt = document.getElementById('as3ar' + itemIndex);
            listtt.innerHTML = '';
            listtt.innerHTML += '<option value="' + prices['retail'] + '">Retail Price: ' + prices['retail'] + '</option>';
            listtt.innerHTML += '<option value="' + prices['whole'] + '">Whole Price: ' + prices['whole'] + '</option>';
            listtt.innerHTML += '<option value="' + prices['inside'] + '">Inside Price: ' + prices['inside'] + '</option>';
           
        } catch(e){
            console.log(e); 
        }
      } 
    };
    http.send(formdata, true);
   }

   var room = 1;
    function addToab() {
   
   room++;
   var objTo = document.getElementById('dynamicContainer')
   var divtest = document.createElement("div");
   divtest.setAttribute("class", "nopadding row col-lg-12 removeclass" + room);
   var rdiv = 'removeclass' + room;
   var concatString = "";
   concatString +=   '<div class="col-lg-6">\
                                <div class="input-group mb-2">\
                                    <select name=item[] class="form-control select2  custom-select" id=inventorySel' + room + ' onchange="changePrices(inventorySel' + room + ')" required>\
                                        <option disabled hidden selected value="">Model</option>\
                                        @foreach($inventory as $item)\
                                        <option value="{{ $item->id }}">\
                                            {{$item->product->PROD_NAME}} - {{$item->product->PROD_ARBC_NAME}} : Available: {{number_format($item->INVT_KGS,2)}}KGs</option>\
                                        @endforeach\
                                    </select>\
                                </div>\
                            </div>';

    concatString += '<div class="col-lg-3">\
                                <div class="input-group mb-3">\
                                    <input  list=as3ar' + room + ' type="number" step="0.01" id=price' + room + ' class="form-control amount" placeholder="Price" min=0 name=price[] aria-describedby="basic-addon11" required>\
                                    <datalist id=as3ar' + room + '></datalist>\
                                </div>\
                            </div>';
    
   concatString +=    " <div class='col-lg-3'>\
                               <div class='input-group mb-3'>\
                                   <input type='number' step=1 class='form-control amount' placeholder='KGs' min=0 id=count" + room + "\
                                       name=count[] \
                                       aria-describedby='basic-addon11' required>\
                                   <div class='input-group-append'>\
                                    <button class='btn btn-danger' type='button' onclick='removeToab(" + room + ");'><i class='fa fa-minus'></i></button>\
                                   </div>\
                               </div>\
                           </div>";
   
   divtest.innerHTML = concatString;
   
   objTo.appendChild(divtest);
   $(".select2").select2()

   }

    function removeToab(rid) {
    $('.removeclass' + rid).remove();

    }


    $(document).on("click", ".open-addItems", function () {
        var accessData = $(this).data('id');
        $(".modal-body #orderIDField").val( accessData );
    });


    function IsJsonString(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }

</script>

@endsection
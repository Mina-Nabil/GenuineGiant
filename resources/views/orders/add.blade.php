@extends('layouts.app')

@section('content')



<div class="row">

    <div class="col-lg-12">
        <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $formTitle }}</h4>
                    <h6 class="card-subtitle">Order Details</h6>

                    @csrf
                    <div class="form-group">
                        <label>Client Or Guest?</label>
                        <div class="input-group mb-3">
                            <select name=guest id=guest class="form-control custom-select" style="width: 100%; height:36px;" onchange="toggleGuest()" required>
                                <option value=1 @if(old('guest')==1) selected @endif>Guest</option>
                                <option value=2 @if(old('guest')==2) selected @endif>Registered User</option>
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('guest')}}</small>
                    </div>

                    <div id=isuser style="display: none">
                        <input type="hidden" name=client id=userID>
                        <div class="form-group">
                            <label>Clients</label>
                            <div class="input-group mb-3">
                                <select name=userSel id=userSel class="select2 form-control custom-select" style="width: 100%; height:36px;" onchange="loadUser()">
                                    <option value="" disabled selected>Pick From Clients</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id }}%%{{ $client->CLNT_AREA_ID}}%%{{$client->CLNT_ADRS}}" @if(old('user')==$client->id)
                                        selected
                                        @endif
                                        >
                                        {{$client->CLNT_NAME}} - {{$client->CLNT_MOBN}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-danger">{{$errors->first('user')}}</small>
                        </div>
                    </div>

                    <div id=isguest style="display: block">
                        <div class="form-group">
                            <label>Guest Name</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Full Name" name=guestName value="{{ old('guestName')}}">
                            </div>
                            <small class="text-danger">{{$errors->first('guestName')}}</small>
                        </div>

                        <div class="form-group">
                            <label>Guest Mobile Number</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Guest Mobile Number" name=guestMob value="{{ old('guestMob')}}">
                            </div>
                            <small class="text-danger">{{$errors->first('guestMob')}}</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Area</label>
                        <div class="input-group mb-3">
                            <select name=area id=areaSel class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                <option value="" disabled selected>Pick From Areas</option>
                                @foreach($areas as $area)
                                <option value="{{ $area->id }}" @if(old('area')==$area->id)
                                    selected
                                    @endif
                                    >{{$area->AREA_NAME}} : {{$area->AREA_ARBC_NAME}}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('area')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Payment Option</label>
                        <div class="input-group mb-3">
                            <select name=option class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                <option value="" disabled selected>Pick From Payment Options</option>
                                @foreach($payOptions as $option)
                                <option value="{{ $option->id }}" @if(old('option')==$option->id)
                                    selected
                                    @endif
                                    >{{$option->PYOP_NAME}}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('option')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Delivery Address</label>
                        <div class="input-group mb-3">
                            <textarea class="form-control" name="address" id=userAdrs rows="3" required>{{old('address')}}</textarea>
                        </div>
                        <small class="text-danger">{{$errors->first('address')}}</small>
                    </div>
                    <div class="form-group">
                        <label>Additional Notes</label>
                        <div class="input-group mb-3">
                            <textarea class="form-control" name="note" rows="3">{{old('note')}}</textarea>
                        </div>
                        <small class="text-danger">{{$errors->first('note')}}</small>
                    </div>


                </div>
            </div>
            <div class=card>
                <div class="card-body">
                    <h4 class="card-title">Order Items</h4>
                    <h6 class="card-subtitle">Pick from our inventory</h6>

                    <div class="row ">

                        <div id="dynamicContainer" class="nopadding row col-lg-12">
                        </div>

                        <div class="row col-lg-12">
                            <div class="col-lg-6">
                                <div class="input-group mb-2">
                                    <select name=item[] class="form-control select2  custom-select" id=inventory1 onchange="changePrices(inventory1)" required>
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
                                    <input list=as3ar1 id=price1 type="number" step="0.01" class="form-control amount" placeholder="Price"  name=price[] aria-describedby="basic-addon11" required>
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

                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    @if($isCancel)
                    <a href="{{url($homeURL) }}" class="btn btn-dark">Cancel</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

</div>
@endsection

@section('js_content')
<script>
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
                                    <select name=item[] class="form-control select2  custom-select" id=inventory' + room + ' onchange="changePrices(inventory' + room + ')" required>\
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
   
   function changePrices(callerID) {
    itemIndex = callerID.id.substring(9, callerID.id.length);
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

   function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

    function toggleGuest(){
    var selectaya = document.getElementById("guest");
        var userDiv = document.getElementById("isuser");
        var guestDiv = document.getElementById("isguest");
        if(selectaya.value == "1") // Guest
        {
            userDiv.style.display = "none";
            guestDiv.style.display = "block";
            $('#areaSel').val(null); // Select the option with a value of '1'
            $('#areaSel').trigger('change');
        } else { // User
     
            userDiv.style.display = "block";
            guestDiv.style.display = "none";
            loadUser();
        }
}

        function loadUser(){
            var selectaya = document.getElementById("userSel");
            var areaSelect = document.getElementById("areaSel");
            userInfo = selectaya.value.split('%%') 
            userInput = document.getElementById("userID");
            userInput.value = userInfo[0]
            var opts = areaSelect.options;

            for (var opt, j = 0; opt = opts[j]; j++) {
                if (opt.value == userInfo[1]) {                 
                    $('#areaSel').val(userInfo[1]); // Select the option with a value of '1'
                    $('#areaSel').trigger('change');
                    break;
                }
            }  
            if(typeof userInfo[2] !== 'undefined'){
            var userAdrs = document.getElementById("userAdrs");
            userAdrs.innerHTML =  userInfo[2]       

            }

        }
</script>
@endsection
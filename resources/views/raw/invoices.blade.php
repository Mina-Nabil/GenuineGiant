@extends('layouts.app')

@section('content')
<div class="row">

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Today's Total</h5>
                <div class="d-flex m-t-30 m-b-20 no-block align-items-center">
                    <span class="display-6 text-danger"><i class="ti-time"></i></span>
                    <a href="javscript:void(0)" class="link display-6 ml-auto">{{number_format($paidToday,2)}}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Current Month</h5>
                <div class="d-flex m-t-30 m-b-20 no-block align-items-center">
                    <span class="display-6 text-success"><i class=" ti-calendar"></i></span>
                    <a href="javscript:void(0)" class="link display-6 ml-auto">{{number_format($paidMonth,2)}}</a>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">RawMaterial Invoices</h4>
                <h6 class="card-subtitle">Check out all raw material invoices </h6>
                <!-- Nav tabs -->
                <div class="vtabs">
                    <ul class="nav nav-tabs tabs-vertical" role="tablist">
                        <li class="nav-item ">
                            <a class="nav-link active" data-toggle="tab" href="#today" role="tab" aria-selected="false">
                                <span class="hidden-sm-up"><i class="ti-time"></i></span>
                                <span class="hidden-xs-down">Today</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#trans" role="tab" aria-selected="false">
                                <span class="hidden-sm-up">
                                    <i class="ti-book"></i></span>
                                <span class="hidden-xs-down">Last 300</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#pay" role="tab" aria-selected="false">
                                <span class="hidden-sm-up">
                                    <i class="fas fa-hand-holding-usd"></i></span>
                                <span class="hidden-xs-down">Add Invoice</span>
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="today" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <x-datatable id="myTable2" :title="$todayTitle" :subtitle="$todaySubTitle" :cols="$cols" :items="$todayInvoices" :atts="$atts" />
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="trans" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <x-datatable id="myTable" :title="$latestTitle" :subtitle="$latestTitle" :cols="$cols" :items="$latestInvoices" :atts="$atts" />
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="pay" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">{{ $formTitle }}</h4>
                                            <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
                                                @csrf

                                                <div class="form-group">
                                                    <label>Supplier</label>
                                                    <div class="input-group mb-2">
                                                        <select name=supplier class="form-control select2  custom-select" style="width:100% " required>
                                                            <option disabled hidden selected value="">Pick from Saved Suppliers</option>
                                                            @foreach($suppliers as $supplier)
                                                            <option value="{{ $supplier->id }}" @if(old('supplier')==$supplier->id)
                                                                selected
                                                                @endif
                                                                > {{$supplier->SUPP_NAME}} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <small class="text-danger">{{$errors->first('supplier')}}</small>
                                                </div>

                                                <hr>
                                                <h4 class="card-title">Invoice Items</h4>
                                                <div class="row ">

                                                    <div id="dynamicContainer" style="width: 100%">
                                                    </div>

                                                    <div class="row col-lg-12">
                                                        <div class="col-lg-6">
                                                            <div class="input-group mb-2">
                                                                <select name=raw[] class="form-control select2  custom-select" style="width:100% " required>
                                                                    <option disabled hidden selected value="">Pick from Raw Materials</option>
                                                                    @foreach($raws as $row)
                                                                    <option value="{{ $row->id }}">
                                                                        {{$row->RWMT_NAME}} - {{$row->RWMT_ARBC_NAME}} </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3">
                                                            <div class="input-group mb-3">
                                                                <input type="number" class="form-control" step=0.001 placeholder="Amount in KGs" name=in[] required>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3">
                                                            <div class="input-group mb-3">
                                                                <input type="number" class="form-control" step=0.01 placeholder="KG Price" name=price[] required>
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-success" id="dynamicAddButton" type="button" onclick="addToab();"><i class="fa fa-plus"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>

                                                <div class="form-group">
                                                    <label>Delivery Fees</label>
                                                    <div class="input-group mb-3">
                                                        <input type="number" class="form-control" step=0.01 placeholder="Delivery Cost" name=delivery value="{{old('delivery') ?? 0}}" required>
                                                    </div>
                                                    <small class="text-danger">{{$errors->first('delivery')}}</small>

                                                </div>

                                                <div class="form-group">
                                                    <label>Additional Comment</label>
                                                    <div class="input-group mb-3">
                                                        <textarea class="form-control" name="comment" rows="3">{{old('comment')}}</textarea>
                                                    </div>
                                                    <small class="text-danger">{{$errors->first('comment')}}</small>
                                                </div>
                                                <hr>
                                                <h3 class="card-title">Invoice Totals</h4>
                                                    <div class="row ">
                                                        <div class="col-lg-4">
                                                            <h4 class="card-title">KGs Total</h4>
                                                            <h5 class="card-subtitle" id=kgs>0 KGs</h5>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <h4 class="card-title">KGs Cost</h4>
                                                            <h5 class="card-subtitle" id=amount>0 EGP</h5>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <h4 class="card-title">Total Cost</h4>
                                                            <h5 class="card-subtitle" id=total>0 EGP</h5>
                                                        </div>
                                                    </div>
                                                    <h4 class="card-title">Invoice Payment</h4>
                                                    <div class="form-group">
                                                        <div class="row ">
                                                            <div class="col-lg-9">
                                                                <div class="input-group mb-3">
                                                                    <input type="number" id=payment class="form-control" step=0.01 placeholder="Total Amount Paid" name=payment
                                                                        value="{{old('payment')}}" required>
                                                                </div>
                                                                <small class="text-danger">{{$errors->first('payment')}}</small>
                                                            </div>
                                                            <div class="col-lg-3">             
                                                                <button type="button" class="btn btn-success mr-2" onclick="payTotal()">Pay total Amount</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button type="button" class="btn btn-info mr-2" onclick="calculateTotal()">Calculate</button>
                                                    <button type="submit" class="btn btn-success mr-2">Add Invoice</button>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
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
   concatString +=   '<div class="row col-lg-12">\
                        <div class="col-lg-6">\
                            <div class="input-group mb-2">\
                                <select name=raw[] class="form-control select2  custom-select" style="width:100% " required>\
                                    <option disabled hidden selected value="">Pick from Raw Materials</option>\
                                    @foreach($raws as $row)\
                                    <option value="{{ $row->id }}">\
                                        {{$row->RWMT_NAME}} - {{$row->RWMT_ARBC_NAME}} </option>\
                                    @endforeach\
                                </select>\
                            </div>\
                        </div>';

    concatString +=   '  <div class="col-lg-3">\
                            <div class="input-group mb-3">\
                                <input type="number" class="form-control" step=0.001 placeholder="Amount in KGs" name=in[] required>\
                            </div>\
                        </div> ';

    concatString +=   '  <div class="col-lg-3">\
                            <div class="input-group mb-3">\
                                <input type="number" class="form-control" step=0.01 placeholder="KG Price" name=price[] required>\
                                <div class="input-group-append">\
                                    <button class="btn btn-danger" type="button" onclick="removeToab(" + room + ");"><i class="fa fa-minus"></i></button>\
                                </div>\
                            </div>\
                        </div>\
                    </div>';
   
   divtest.innerHTML = concatString;
   
   objTo.appendChild(divtest);
   $(".select2").select2()

   }

   function removeToab(rid) {
    $('.removeclass' + rid).remove();

    }

    function payTotal(){
        cost = calculateTotal();
        pay = document.getElementById('payment')
        pay.value = cost

    }

    function calculateTotal() {
        kgs = document.getElementsByName('in[]');
        cost = document.getElementsByName('price[]');
        i = 0
        totalKGS = 0
        totalCost = 0
        cost.forEach(element => {
            totalKGS += parseFloat(kgs[i].value)
            totalCost += parseFloat(element.value) * parseFloat(kgs[i].value)
            i++
        });
        costLabel = document.getElementById('amount')
        costLabel.innerHTML = totalCost  + " EGP"

        deliveryFees = document.getElementsByName('delivery')[0]
        totalCost += parseFloat(deliveryFees.value )

        totalLabel = document.getElementById('total')
        totalLabel.innerHTML = totalCost  + " EGP"
        
        kgsLabel = document.getElementById('kgs')
        kgsLabel.innerHTML = totalKGS  + " KG"

        return totalCost
    }
</script>
@endsection
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total KGs Supplied</h5>
                <div class="d-flex m-t-30 m-b-20 no-block align-items-center">
                    <span class="display-5 text-info"><i class=" ti-package"></i></span>
                    <a href="javscript:void(0)" class="link display-5 ml-auto">{{number_format($totalKG,2)}}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Paid</h5>
                <div class="d-flex m-t-30 m-b-20 no-block align-items-center">
                    <span class="display-5 text-success"><i class="fas fa-hand-holding-usd"></i></span>
                    <a href="javscript:void(0)" class="link display-5 ml-auto">{{number_format($totalPaid,2)}}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Balance</h5>
                <div class="d-flex m-t-30 m-b-20 no-block align-items-center">
                    <span class="display-5 text-success"><i class="ti-money"></i></span>
                    <a href="javscript:void(0)" class="link display-5 ml-auto">{{number_format($balance,2)}}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Transactions and Payments</h4>
                <h6 class="card-subtitle">Check out all transactions and payments for {{$supplier->SUPP_NAME}}</h6>
                <!-- Nav tabs -->
                <div class="vtabs">
                    <ul class="nav nav-tabs tabs-vertical" role="tablist">
                        <li class="nav-item ">
                            <a class="nav-link active" data-toggle="tab" href="#info" role="tab" aria-selected="false">
                                <span class="hidden-sm-up"><i class="icon-user"></i></span>
                                <span class="hidden-xs-down">Info</span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" data-toggle="tab" href="#stock" role="tab" aria-selected="false">
                                <span class="hidden-sm-up"><i class="icon-social-dropbox"></i></span>
                                <span class="hidden-xs-down">Related Stock</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#trans" role="tab" aria-selected="false">
                                <span class="hidden-sm-up">
                                    <i class="icon-layers"></i></span>
                                <span class="hidden-xs-down">Entries</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#paid" role="tab" aria-selected="false">
                                <span class="hidden-sm-up">
                                    <i class="icon-book-open"></i></span>
                                <span class="hidden-xs-down">Account</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#pay" role="tab" aria-selected="false">
                                <span class="hidden-sm-up">
                                    <i class="fas fa-hand-holding-usd"></i></span>
                                <span class="hidden-xs-down">Add Payment</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#edit" role="tab" aria-selected="false">
                                <span class="hidden-sm-up">
                                    <i class="fas fa-user"></i></span>
                                <span class="hidden-xs-down">Manage Supplier</span>
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="info" role="tabpanel">
                            <div class="row">
                                <div class="col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <small class="text-muted">Supplier Name </small>
                                            <h6>{{$supplier->SUPP_NAME}}</h6>
                                            <small class="text-muted p-t-30 db">Phone</small>
                                            <h6>{{$supplier->SUPP_MOBN}}</h6>
                                            <small class="text-muted p-t-30 db">Balance</small>
                                            <h6>{{$supplier->SUPP_BLNC}}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <small class="text-muted">Supply Deals </small>
                                            @foreach ($supplies as $deal)
                                            <h6>{{$deal->RWMT_NAME}} for {{number_format($deal->SPLS_PRCE, 2)}} EGP</h6>
                                            <br>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="stock" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <x-datatable id="myTable" :title="$rawTitle" :subtitle="$rawSubtitle" :cols="$rawCols" :items="$bought" :atts="$rawAtts" />
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="trans" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <x-datatable id="myTable2" :title="$transTitle" :subtitle="$transSubtitle" :cols="$transCols" :items="$trans" :atts="$transAtts" />
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="paid" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <x-datatable id="myTable3" :title="$payTitle" :subtitle="$paySubtitle" :cols="$payCols" :items="$pays" :atts="$payAtts" />
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
                                                <input type=hidden name=id value="{{(isset($supplier)) ? $supplier->id : ''}}">

                                                <div class="form-group">
                                                    <label>Amount*</label>
                                                    <div class="input-group mb-3">
                                                        <input type="number" step=0.01 class="form-control" placeholder="Payment Amount" name=amount value="0" required>
                                                    </div>
                                                    <small class="text-danger">{{$errors->first('amount')}}</small>
                                                </div>

                                                <div class="form-group">
                                                    <label>Comment</label>
                                                    <div class="input-group mb-3">
                                                        <textarea class="form-control" rows="2" name="comment"></textarea>
                                                    </div>

                                                    <small class="text-danger">{{$errors->first('comment')}}</small>
                                                </div>

                                                <button type="submit" class="btn btn-success mr-2">Add Payment</button>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="edit" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">{{ $infoFormTitle }}</h4>
                                            <form class="form pt-3" method="post" action="{{ url($infoFormURL) }}" enctype="multipart/form-data">
                                                @csrf
                                                <input type=hidden name=id value="{{(isset($supplier)) ? $supplier->id : ''}}">
                                                <div class="form-group">
                                                    <label>Supplier Name*</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" placeholder="Full Name" name=name value="{{ (isset($supplier)) ? $supplier->SUPP_NAME : old('name')}}"
                                                            required>
                                                    </div>
                                                    <small class="text-danger">{{$errors->first('name')}}</small>
                                                </div>


                                                <div class="form-group">
                                                    <label>Mobile Number*</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" placeholder="Mobile Number" name=mob
                                                            value="{{ (isset($supplier)) ? $supplier->SUPP_MOBN : old('mob') }}" required>
                                                    </div>

                                                    <small class="text-danger">{{$errors->first('mob')}}</small>
                                                </div>
                                                <div class="form-group">
                                                    <label>Balance*</label>
                                                    <div class="input-group mb-3">
                                                        <input type="number" step=0.01 class="form-control" placeholder="Supplier Balance" name=balance
                                                            value="{{ (isset($supplier)) ? $supplier->SUPP_BLNC : (old('balance') ?? 0)}}" required>
                                                    </div>
                                                    <small class="text-danger">{{$errors->first('balance')}}</small>
                                                </div>

                                                <hr>

                                                <h4 class="card-title">Supplied Raw Materials</h4>
                                                <div class="row ">


                                                    <?php 
                                                        $i=1;
                                                        $max = count($supplies);
                                                        ?>
                                                    @foreach ($supplies as $deal)
                                                    <div class="row col-lg-12 {{ ($i != $max) ? 'removeclass' . $i : '' }}">
                                                        <div class="col-lg-8">
                                                            <div class="input-group mb-2">
                                                                <select name=raw[] class="form-control select2  custom-select" style="width:100% " required>
                                                                    <option disabled hidden selected value="">Pick from Raw Materials</option>
                                                                    @foreach($raws as $row)
                                                                    <option value="{{ $row->id }}" @if ($deal->id == $row->id)
                                                                        selected
                                                                        @endif
                                                                        >
                                                                        {{$row->RWMT_NAME}} - {{$row->RWMT_ARBC_NAME}} </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4">
                                                            <div class="input-group mb-3">
                                                                <input type="number" class="form-control" step=0.01 placeholder="KG Price" name=price[] value="{{$deal->SPLS_PRCE}}" required>
                                                                <div class="input-group-append">
                                                                    @if ($i == $max)
                                                                    <button class="btn btn-success" id="dynamicAddButton" type="button" onclick="addToab();"><i class="fa fa-plus"></i></button>
                                                                    @else
                                                                    <button class="btn btn-danger" type="button" onclick="removeToab({{$i}});"><i
                                                                            class="fa fa-minus"></i></button>
                                                                    @endif

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php $i++; ?>
                                                    @endforeach

                                                    <hr>

                                                    <div id="dynamicContainer" style="width: 100%">
                                                    </div>



                                                </div>


                                                <button type="submit" class="btn btn-success mr-2">Submit</button>
                                                @if($isCancel)
                                                <a href="{{url($homeURL) }}" class="btn btn-dark">Cancel</a>
                                                @endif
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
<div class="row">
    <div class="col-12">
        {{-- <x-datatable id="myTable" :title="$title" :subtitle="$subTitle" :cols="$cols" :items="$items" :atts="$atts" /> --}}
    </div>
</div>
@endsection


@section('js_content')

<script>
    var room = {{$i}};
   function addToab() {
   
   room++;
   var objTo = document.getElementById('dynamicContainer')
   var divtest = document.createElement("div");
   divtest.setAttribute("class", "nopadding row  removeclass" + room);
   var rdiv = 'removeclass' + room;
   var concatString = "";
   concatString +=   '<div class="row col-lg-12">\
                        <div class="col-lg-8">\
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

    concatString +=   '  <div class="col-lg-4">\
                            <div class="input-group mb-3">\
                                <input type="number" class="form-control" step=0.01 placeholder="KG Price" name=price[] required>\
                                <div class="input-group-append">\
                                    <button class="btn btn-danger" type="button" onclick="removeToab(' + room + ');"><i class="fa fa-minus"></i></button>\
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

</script>

@endsection
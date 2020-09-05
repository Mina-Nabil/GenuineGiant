@extends('layouts.app')

@section('head_content')

<link href="{{asset('assets/node_modules/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/node_modules/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

<div class="row">
    <!-- Column -->
    <div class="col-lg-4 col-xlg-3 col-md-5">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-xs-6 b-r"> <strong>Model Name</strong>
                        <br>
                        <p class="text-muted">{{$product->PROD_NAME}}</p>
                    </div>
                    <div class="col-md-6 col-xs-6 b-r"> <strong>Arabic Name</strong>
                        <br>
                        <p class="text-muted">{{$product->PROD_ARBC_NAME}}</p>
                    </div>
                </div>
                <hr>
                <strong>Description</strong>
                <p class="m-t-30">{{$product->PROD_DESC}}</p>
                <hr>
                <strong>Arabic Description</strong>
                <p class="m-t-30">{{$product->PROD_ARBC_DESC}}</p>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-8 col-xlg-9 col-md-7">
        <div class="card">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs profile-tab" role="tablist">
                <li class="nav-item active"> <a class="nav-link" data-toggle="tab" href="#sales" role="tab">Sales</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#stock" role="tab">Stock</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#ingredients" role="tab">Ingredients</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab">Settings</a> </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!--second tab-->
                <div class="tab-pane active" id="sales" role="tabpanel">
                    <div class="card-body">
                        {{-- <x-simple-chart :chartTitle="$totalTitle ?? ''" :chartSubtitle="$totalSubtitle ?? ''" :graphs="$totalGraphs ?? ''" :totals="$totalTotals ?? ''"  /> --}}
                    </div>
                </div>

                <div class="tab-pane" id="stock" role="tabpanel">
                    <div class="card-body">
                        <x-datatable id="myTable" :title="$title" :subtitle="$subTitle" :cols="$cols" :items="$items" :atts="$atts" />
                    </div>
                </div>

                <div class="tab-pane" id="ingredients" role="tabpanel">
                    <div class="card-body">
                        <x-datatable id="myTable2" :title="$rawTitle" :subtitle="$rawSubTitle" :cols="$rawCols" :items="$rawItems" :atts="$rawAtts" />
                        <hr>
                        <div class="card-title">Add New Ingredients</div>
                        <form class="form pt-3" method="post" action="{{ url($addIngredients) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row ">
                                <div id="dynamicContainer" class="nopadding row col-lg-12">
                                </div>

                                <div class="row col-lg-12 nopadding">
                                    <div class="col-lg-9">
                                        <div class="input-group mb-2">
                                            <select name=item[] class="form-control select2 custom-select" style="width: 100%" required>
                                                <option disabled hidden selected value="">Raw Materials</option>
                                                @foreach($raw as $row)
                                                <option value="{{ $row->id }}"> {{$row->RWMT_NAME}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="input-group mb-3">
                                            <input type="number" step=1 class="form-control amount" placeholder="Grams" min=0 name=grams[] aria-describedby="basic-addon11" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-success" id="dynamicAddButton" type="button" onclick="addToab();"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success mr-2">Submit</button>
                        </form>
                    </div>
                </div>

                <div class="tab-pane" id="settings" role="tabpanel">
                    <div class="card-body">
                        <h4 class="card-title">{{ $formTitle }}</h4>
                        <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
                            @csrf
                            <input type=hidden name=id value="{{(isset($product)) ? $product->id : ''}}">

                            <div class="form-group">
                                <label>Category</label>
                                <div class="input-group mb-3">
                                    <select name=category class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                        <option value="" disabled selected>Pick From Categories</option>
                                        @foreach($categories as $categry)
                                        <option value="{{ $categry->id }}" @if(isset($product) && $categry->id == $product->PROD_SBCT_ID)
                                            selected
                                            @elseif(old('category') == $categry->id)
                                            selected
                                            @endif
                                            >{{$categry->category->CATG_NAME}} : {{$categry->SBCT_NAME}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-danger">{{$errors->first('category')}}</small>
                            </div>

                            <div class="form-group">
                                <label>Model Title</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Full Model Title" name=name value="{{ (isset($product)) ? $product->PROD_NAME : old('name')}}" required>
                                </div>
                                <small class="text-danger">{{$errors->first('name')}}</small>
                            </div>
                            <div class="form-group">
                                <label>Arabic Title</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name=arbcName placeholder="اسم الموديل بالعربيه" value="{{ (isset($product)) ? $product->PROD_ARBC_NAME : old('mail')}}">
                                </div>
                                <small class="text-danger">{{$errors->first('arbcName')}}</small>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" name=desc>{{(isset($product)) ? $product->PROD_DESC : old('desc')}}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Arabic Description</label>
                                <textarea class="form-control" rows="3" name=arbcDesc>{{(isset($product)) ? $product->PROD_ARBC_DESC : old('desc')}}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Retail Price</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" step=0.01 placeholder="Normal Price per kilo" name=retailPrice
                                        value="{{ (isset($product)) ? $product->PROD_RETL_PRCE : old('retailPrice')}}" required>
                                </div>
                                <small class="text-danger">{{$errors->first('retailPrice')}}</small>
                            </div>

                            <div class="form-group">
                                <label>Whole Price</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" step=0.01 placeholder="Price for bulk sales" name=wholePrice
                                        value="{{ (isset($product)) ? $product->PROD_WHLE_PRCE : old('wholePrice')}}" required>
                                </div>
                                <small class="text-danger">{{$errors->first('wholePrice')}}</small>
                            </div>

                            <div class="form-group">
                                <label>Inside Price</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" step=0.01 placeholder="Lowest price for huge sales" name=insidePrice
                                        value="{{ (isset($product)) ? $product->PROD_INSD_PRCE : old('insidePrice')}}" required>
                                </div>
                                <small class="text-danger">{{$errors->first('insidePrice')}}</small>
                            </div>

                            <div class="form-group">
                                <label>Cost</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" placeholder="Model Cost" name=cost value="{{ (isset($product)) ? $product->PROD_COST : old('cost') }}">
                                </div>
                                @if($errors->first('cost') !=null)
                                <small class="text-danger">{{$errors->first('cost')}}</small>
                                @else
                                <small>Not Required</small>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-success mr-2">Submit</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
</div>
@endsection

@section("js_content")
<script type="text/javascript" src="{{asset('assets/node_modules/multiselect/js/jquery.multi-select.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/node_modules/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>

<script>
    var room = 1;
   function addToab() {
   
   room++;
   var objTo = document.getElementById('dynamicContainer')
   var divtest = document.createElement("div");
   divtest.setAttribute("class", "nopadding row col-lg-12 removeclass" + room);
   var rdiv = 'removeclass' + room;
   var concatString = "";
   concatString +=   '<div class="row col-lg-12 nopadding">\
                                    <div class="col-lg-9">\
                                        <div class="input-group mb-2">\
                                            <select name=item[] class="form-control select2 custom-select" style="width: 100%" required>\
                                                <option disabled hidden selected value="">Raw Materials</option>\
                                                @foreach($raw as $row)\
                                                <option value="{{ $row->id }}"> {{$row->RWMT_NAME}} </option>\
                                                @endforeach\
                                            </select>\
                                        </div>\
                                    </div>';

    concatString +=    '<div class="col-lg-3">\
                                        <div class="input-group mb-3">\
                                            <input type="number" step=1 class="form-control amount" placeholder="Grams" min=0 name="grams[]" aria-describedby="basic-addon11" required>\
                                            <div class="input-group-append">\
                                                <button class="btn btn-danger" id="dynamicAddButton" type="button" onclick="removeToab(' + room + ');"><i class="fa fa-minus"></i></button>\
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
   
   function changeMax(callerID) {
       itemIndex = callerID.id.substring(9, callerID.id.length)
        count = document.getElementById('count' + itemIndex) 
        optionString = callerID.options[callerID.selectedIndex].innerHTML
        count.max = optionString.substring(optionString.indexOf(":", optionString.length-10)+1 ,optionString.length)
   }
</script>

@endsection
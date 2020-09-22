@extends('layouts.app')

@section('content')



<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $formTitle }}</h4>
                <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
                    @csrf
                    <input type=hidden name=id value="{{(isset($supplier)) ? $supplier->id : ''}}">
                    <div class="form-group">
                        <label>Supplier Name*</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Full Name" name=name value="{{ (isset($supplier)) ? $supplier->SUPP_NAME : old('name')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>


                    <div class="form-group">
                        <label>Mobile Number*</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Mobile Number" name=mob value="{{ (isset($supplier)) ? $supplier->SUPP_MOBN : old('mob') }}" required>
                        </div>

                        <small class="text-danger">{{$errors->first('mob')}}</small>
                    </div>
                    <div class="form-group">
                        <label>Balance*</label>
                        <div class="input-group mb-3">
                            <input type="number" step=0.01 class="form-control" placeholder="Supplier Balance" name=balance value="{{ (isset($supplier)) ? $supplier->SUPP_BLNC : (old('balance') ?? 0)}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('balance')}}</small>
                    </div>
                    
                    <hr>

                    <h4 class="card-title">Supplied Raw Materials</h4>
                    <div class="row ">

                        <div id="dynamicContainer" style="width: 100%">
                        </div>

                        <div class="row col-lg-12">
                            <div class="col-lg-8">
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

                            <div class="col-lg-4">
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" step=0.01 placeholder="KG Price" name=price[] required>
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
                </form>
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

</script>
    
@endsection
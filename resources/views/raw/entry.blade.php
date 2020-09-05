@extends('layouts.app')

@section('content')
<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $formTitle }}</h4>
                <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>Transaction Title</label>
                        <div class="input-group mb-3">
                            <input type="text" list=desc class="form-control" name=desc placeholder="Title" value="{{old('desc')}}" required>
                            <datalist id=desc>
                                <option value="Whole Buy">
                                <option value="Retail Buy">
                            </datalist>
                        </div>
                        <small class="text-danger">{{$errors->first('desc')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Raw Material</label>
                        <div class="input-group mb-2">
                            <select name=raw class="form-control select2  custom-select" style="width:100% " required>
                                <option disabled hidden selected value="">Pick from Raw Materials</option>
                                @foreach($raws as $row)
                                <option value="{{ $row->id }}">
                                    {{$row->RWMT_NAME}} - {{$row->RWMT_ARBC_NAME}} </option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('raw')}}</small>
                    </div>

                    <div class="form-group">
                        <label>In/Out</label>
                        <div class="input-group mb-2">
                            <select id=inSel class="form-control " onchange="selectInOut()" required>
                                <option selected value="in">In</option>
                                <option value="out">Out</option>
                            </select>
                        </div>
                    </div>

                    <div id="indev" style="display: block">
                        <div class="form-group">
                            <label>Supplier</label>
                            <div class="input-group mb-2">
                                <select name=supplier class="form-control select2  custom-select" style="width:100% " required>
                                    <option disabled hidden selected value="">Pick from Saved Suppliers</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">  {{$supplier->SUPP_NAME}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-danger">{{$errors->first('supplier')}}</small>
                        </div>
                        <div class="form-group">
                            <label>In Amount</label>
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" step=0.001 placeholder="Amount in KGs" name=in value="{{old('in')}}">
                            </div>
                            <small class="text-danger">{{$errors->first('wholePrice')}}</small>
                        </div>

                        <div class="form-group">
                            <label>Price per KG</label>
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" step=0.01 placeholder="KG Price " name=price value="{{old('price')}}">
                            </div>
                            <small class="text-danger">{{$errors->first('price')}}</small>
                        </div>
                    </div>
                    <div id=outdev style="display: none">
                        <div class="form-group">
                            <label>Out Amount</label>
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" step=0.001 placeholder="Amount in KGs" name=out value="{{old('out')}}">
                            </div>
                            <small class="text-danger">{{$errors->first('wholePrice')}}</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Additional Comments</label>
                        <div class="input-group mb-3">
                            <textarea class="form-control" name="comment" rows="3">{{old('comment')}}</textarea>
                        </div>
                        <small class="text-danger">{{$errors->first('comment')}}</small>
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                </form>
            </div>
        </div>
    </div>

</div>


<script>
    function selectInOut(inSel){
    selected = document.getElementById("inSel").selectedIndex;
    if(selected == 0){
        document.getElementById("indev").style="display: block";
        document.getElementById("outdev").style="display: none";
    } else {
        document.getElementById("indev").style="display: none";
        document.getElementById("outdev").style="display: block";
    }
}
   
</script>

@endsection

@section("js_content")

@endsection
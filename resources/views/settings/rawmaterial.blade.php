@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-8">
        <x-datatable id="myTable" :title="$title" :subtitle="$subTitle" :cols="$cols" :items="$items" :atts="$atts" />
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $formTitle }}</h4>
                <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data" >
                @csrf
                <input type=hidden name=id value="{{(isset($raw)) ? $raw->id : ''}}">

                    <div class="form-group">
                        <label>Raw Material Name*</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Raw Material Name" name=name value="{{ (isset($raw)) ? $raw->RWMT_NAME : old('name')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>
                    <div class="form-group">
                        <label>Arabic Name</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Arabic Name" name=arbcName value="{{ (isset($raw)) ? $raw->RWMT_ARBC_NAME : old('arbcName')}}" >
                        </div>
                        <small class="text-danger">{{$errors->first('arbcName')}}</small>
                    </div>
                    <div class="form-group">
                        <label>Calculated Cost </label>
                        <div class="input-group mb-3">
                            <input type="numerice" step=0.01 class="form-control" placeholder="Calculated Cost per Kilogram" name=cost value="{{ (isset($raw)) ? $raw->RWMT_COST : old('cost')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('cost')}}</small>
                    </div>
                    <div class="form-group">
                        <label>Reference Cost </label>
                        <div class="input-group mb-3">
                            <input type="numerice" step=0.01 class="form-control" placeholder="Estimated Cost per Kilogram " name=estimated value="{{ (isset($raw)) ? $raw->RWMT_ESTM_COST : old('estimated')}}" >
                        </div>
                        <small class="text-danger">{{$errors->first('cost')}}</small>
                    </div>
                    <div class="form-group">
                        <label>Balance</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Current Kg stored" name=balance value="{{ (isset($raw)) ? $raw->RWMT_BLNC : old('balance')}}" >
                        </div>
                        <small class="text-danger">{{$errors->first('balance')}}</small>
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
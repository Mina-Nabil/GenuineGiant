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

                    <div class="form-group">
                        <label>Supplies</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Supplied Items (Chicken, Kebda..)" name=business value="{{ (isset($supplier)) ? $supplier->SUPP_BSNS : old('business') }}">
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
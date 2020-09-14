@extends('layouts.app')

@section('content')



<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $formTitle }}</h4>
                <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
                    @csrf
                    <input type=hidden name=id value="{{(isset($client)) ? $client->id : ''}}">
                    <div class="form-group">
                        <label>Full Name*</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Full Name" name=name value="{{ (isset($client)) ? $client->CLNT_NAME : old('name')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>


                    <div class="form-group">
                        <label>Mobile Number*</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Mobile Number" name=mob value="{{ (isset($client)) ? $client->CLNT_MOBN : old('mob') }}" required>
                        </div>

                        <small class="text-danger">{{$errors->first('mob')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Balance</label>
                        <div class="input-group mb-3">
                            <input type="number" step="0.01" class="form-control" placeholder="Client Balance, amount of money we have to collect from him" name=balance
                                value="{{ (isset($client)) ? $client->CLNT_BLNC : old('balance') }}" required>
                        </div>

                        <small class="text-danger">{{$errors->first('balance')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Average Usage</label>
                        <div class="input-group mb-3">
                            <input type="number" step="0.01" class="form-control" placeholder="Client Average use in Kilograms" name=avg
                                value="{{ (isset($client)) ? $client->CLNT_AVGK : old('avg') }}">
                        </div>

                        <small class="text-danger">{{$errors->first('avg')}}</small>
                    </div>



                    <div class="form-group">
                        <label>Area*</label>
                        <div class="input-group mb-3">
                            <select name=area class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                <option value="" disabled selected>Select Area</option>
                                @foreach($areas as $area)
                                <option value="{{ $area->id }}" @if(isset($client) && $area->id == $client->CLNT_AREA_ID)
                                    selected
                                    @elseif($area->id == old('area'))
                                    selected
                                    @endif
                                    >{{$area->AREA_NAME}} - {{$area->AREA_ARBC_NAME}}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('area')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <div class="input-group mb-3">
                            <textarea class="form-control" rows="3" name="address">{{ (isset($client)) ? $client->CLNT_ADRS : old('address') }}</textarea>
                        </div>

                        <small class="text-danger">{{$errors->first('balance')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Lattitude</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Pin Lattitude" name=latt value="{{ (isset($client)) ? $client->CLNT_LATT : old('latt') }}">
                        </div>

                        <small class="text-danger">{{$errors->first('latt')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Longitude</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Pin Longitude" name=long value="{{ (isset($client)) ? $client->CLNT_LONG : old('long') }}">
                        </div>

                        <small class="text-danger">{{$errors->first('long')}}</small>
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
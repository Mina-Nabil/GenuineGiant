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
                <input type=hidden name=id value="{{(isset($slot)) ? $slot->id : ''}}">

                    <div class="form-group">
                        <label>Slot Name*</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Delivery Slot Name" name=name value="{{ (isset($slot)) ? $slot->DSLT_NAME : old('name')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Slot Start Time</label>
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" placeholder="Delivery Slot Start Time" name=start value="{{ (isset($slot)) ? $slot->DSLT_STRT->format('H:i') : old('start')}}">
                        </div>
                        <small class="text-danger">{{$errors->first('start')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Slot End Time</label>
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" placeholder="Delivery Slot End Time" name=end value="{{ (isset($slot)) ? $slot->DSLT_END->format('H:i') : old('end')}}">
                        </div>
                        <small class="text-danger">{{$errors->first('end')}}</small>
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
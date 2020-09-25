@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">New Orders</h4>
                <h6 class="card-subtitle">Load Upcoming Orders</h6>
                <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Date</label>
                        <div class="input-group mb-3">
                            <input type="date" value="{{now()->format('Y-m-d')}}" class="form-control" placeholder="Pick a date" name=date  required />
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Shift</label>
                        <div class="input-group mb-3">
                            <select name=slot class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                <option value="-1">All Day</option>
                                @foreach ($slots as $slot)
                                <option value="{{$slot->id}}">{{$slot->DSLT_NAME}} -- {{$slot->DSLT_STRT->format('H:i')}} : {{$slot->DSLT_END->format('H:i')}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $formTitle }}</h4>
                <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
                    @csrf

                    @foreach ($types as $type)



                    <div class="form-group">
                        <label>{{$type->DHTP_NAME}} Modules</label>
                        <div class="input-group mb-3">
                            <select class="select2 m-b-10 select2-multiple" style="width: 100%" multiple="multiple" data-placeholder="Choose From The Following Modules" name='{{$type->DHTP_NAME}}[]'>
                                @foreach($modules as $module)
                                <option value="{{$module->id}}" {{(in_array( $module->id, $type->modules->pluck('id')->all())) ? 'selected' : ''}}>{{$module->MDUL_NAME}}</option>
                                @endforeach
                            </select>
                        </div>
                  
                    </div>
                    @endforeach

                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
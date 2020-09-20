@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-7">
        <x-datatable id="myTable" :title="$title" :subtitle="$subTitle" :cols="$cols" :items="$items" :atts="$atts" />
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $formTitle }}</h4>
                <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data" >
                @csrf
                <input type=hidden name=id value="{{(isset($subcategory)) ? $subcategory->id : ''}}">

                    <div class="form-group">
                        <label>Sub Category Name*</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Sub Category Name" name=name value="{{ (isset($subcategory)) ? $subcategory->SBCT_NAME : old('name')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>
                    <div class="form-group">
                        <label>Arabic Name*</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Arabic Name" name=arbcName value="{{ (isset($subcategory)) ? $subcategory->SBCT_ARBC_NAME : old('arbcName')}}" >
                        </div>
                        <small class="text-danger">{{$errors->first('arbcName')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Category*</label>
                        <div class="input-group mb-3">
                            <select name=category class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                <option value="" disabled selected >Main Categories</option>
                                @foreach($categories as $categry)
                                <option value="{{ $categry->id }}"
                                @if(isset($subcategory) && $categry->id == $subcategory->SBCT_CATG_ID)
                                    selected
                                @endif
                                >{{$categry->CATG_NAME}} - {{$categry->CATG_ARBC_NAME}}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('category')}}</small>
                    </div>

                    <div class="form-group">
                        <label for="input-file-now-custom-1">Image</label>
                          <div class="input-group mb-3">
                              <input type="file" id="input-file-now-custom-1" name=photo class="dropify" data-default-file="{{ (isset($subcategory->SBCT_IMGE)) ? asset( 'storage/'. $subcategory->SBCT_IMGE ) : old('photo') }}" />
                          </div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <div class="input-group mb-3">
                           <textarea class="form-control" rows="3" name="desc">{{ (isset($subcategory)) ? $subcategory->SBCT_DESC : old('desc') }}</textarea>
                        </div>
                
                        <small class="text-danger">{{$errors->first('desc')}}</small>
                    </div>
            

                    

                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    @if($isCancel)
                        <a href="{{url($homeURL) }}" class="btn btn-dark">Cancel</a>
                    @endif
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $form2Title }}</h4>
                <form class="form pt-3" method="post" action="{{ url($form2URL) }}" enctype="multipart/form-data" >
                @csrf
                <input type=hidden name=id value="{{(isset($category)) ? $category->id : ''}}">

                    <div class="form-group">
                        <label>Category Name*</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Category Name" name=catgName value="{{ (isset($category)) ? $category->CATG_NAME : old('catgName')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('catgName')}}</small>
                    </div>
                    <div class="form-group">
                        <label>Arabic Name*</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Arabic Category Name" name=arbcName value="{{ (isset($category)) ? $category->CATG_ARBC_NAME : old('arbcName')}}" >
                        </div>
                        <small class="text-danger">{{$errors->first('arbcName')}}</small>
                    </div>
                    
                    
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    @if($isCancel2)
                        <a href="{{url($homeURL) }}" class="btn btn-dark">Cancel</a>
                    @endif
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
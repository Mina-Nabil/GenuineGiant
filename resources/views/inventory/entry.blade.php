@extends('layouts.app')

@section('content')
<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $formTitle }}</h4>
                <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row ">

                        <div id="dynamicContainer" class="nopadding row col-lg-12">
                        </div>

                        <div class="row col-lg-12">
                            <div class="col-lg-8">
                                <div class="input-group mb-2">
                                    <select name=model[] class="form-control select2  custom-select" required>
                                        <option disabled hidden selected value="">Products</option>
                                        @foreach($products as $model)
                                        <option value="{{ $model->id }}">
                                            {{$model->PROD_NAME}} - {{$model->PROD_ARBC_NAME}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
    

                            <div class="col-lg-4">
                                <div class="input-group mb-3">
                                    <input type="number" step=1 class="form-control amount" placeholder="KGs" name=count[] aria-describedby="basic-addon11" required>
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
    </div>

</div>


<script>
    var room = 1;
   function addToab() {
   
   room++;
   var objTo = document.getElementById('dynamicContainer')
   var divtest = document.createElement("div");
   divtest.setAttribute("class", "nopadding row col-lg-12 removeclass" + room);
   var rdiv = 'removeclass' + room;
   var concatString = "";
   concatString +=   "  <div class='col-lg-8'>\
                               <div class='input-group mb-2'>\
                                   <select  name=model[] class='select2 form-control  custom-select'\
                                       required>\
                                       <option disabled hidden selected value=''>Products</option>\
                                       @foreach($products as $model)\
                                       <option value='{{ $model->id }}'>\
                                           {{$model->PROD_NAME}} - {{$model->PROD_ARBC_NAME}} </option>\
                                       @endforeach\
                                   </select>\
                               </div>\
                           </div>";

   
   concatString +=                    " <div class='col-lg-4'>\
                               <div class='input-group mb-3'>\
                                   <input type='number' step=1 class='form-control amount' placeholder='KGs'\
                                       name=count[] \
                                       aria-describedby='basic-addon11' required>\
                                   <div class='input-group-append'>\
                                    <button class='btn btn-danger' type='button' onclick='removeToab(" + room + ");'><i class='fa fa-minus'></i></button>\
                                   </div>\
                               </div>\
                           </div>";
   
   divtest.innerHTML = concatString;
   
   objTo.appendChild(divtest);
   $(".select2").select2()

   }

   function removeToab(rid) {
    $('.removeclass' + rid).remove();

}
   
</script>

@endsection

@section("js_content")

@endsection
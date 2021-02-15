@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total KGs</h5>
                <div class="d-flex m-t-30 m-b-20 no-block align-items-center">
                    <span class="display-5 text-info"><i class=" ti-package"></i></span>
                    <a href="javscript:void(0)" class="link display-5 ml-auto">{{number_format($totalKG,2)}}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Retail Price Sum</h5>
                <div class="d-flex m-t-30 m-b-20 no-block align-items-center">
                    <span class="display-5 text-warning"><i class="ti-money"></i></span>
                    <a href="javscript:void(0)" class="link display-5 ml-auto">{{number_format($totalPrice,2)}}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Cost</h5>
                <div class="d-flex m-t-30 m-b-20 no-block align-items-center">
                    <span class="display-5 text-success"><i class="ti-money"></i></span>
                    <a href="javscript:void(0)" class="link display-5 ml-auto">{{number_format($totalCost,2)}}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Product Inventory</h4>
                <h6 class="card-subtitle">Check current Raw Materials Stock List and latest transactions</h6>
                <!-- Nav tabs -->
                <div class="vtabs">
                    <ul class="nav nav-tabs tabs-vertical" role="tablist">
                        <li class="nav-item ">
                            <a class="nav-link active" data-toggle="tab" href="#stock" role="tab" aria-selected="false">
                                <span class="hidden-sm-up"><i class="icon-social-dropbox"></i></span>
                                <span class="hidden-xs-down">Stock List</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#trans" role="tab" aria-selected="false">
                                <span class="hidden-sm-up">
                                    <i class="icon-graph"></i></span>
                                <span class="hidden-xs-down">Transactions</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#add" role="tab" aria-selected="false">
                                <span class="hidden-sm-up">
                                    <i class="icon-graph"></i></span>
                                <span class="hidden-xs-down">Add Entry</span>
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="stock" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <x-datatable id="myTable" :title="$stockTitle" :subtitle="$stockSubtitle" :cols="$stockCols" :items="$items" :atts="$stockAtts" />
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="trans" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <x-datatable id="myTable2" :title="$transTitle" :subtitle="$transSubtitle" :cols="$transCols" :items="$trans" :atts="$transAtts" />
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="add" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">{{ $formTitle }}</h4>
                                            <h5 class="card-subtitle">Insert a new Production entry, after entry the ingredients will be calculated for raw materials consumptions</h5>
                                            <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row ">
                            
                                                    <div id="dynamicContainer" class="nopadding row col-lg-12">
                                                    </div>
                            
                                                    <div class="row col-lg-12">
                                                        <div class="col-lg-8">
                                                            <div class="input-group mb-2">
                                                                <select name=model[] class="form-control select2  custom-select" style="width: 100%" required>
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
                        </div>

                    </div>
                </div>
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
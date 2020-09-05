@extends('layouts.app')


@section('content')

<div class="row">
    <!-- Column -->
    <div class="col-lg-4 col-xlg-3 col-md-5">
        <div class="card">
            <div class="card-body">
                <small class="text-muted">Client Name </small>
                <h6>{{$client->CLNT_NAME}}</h6>
                <small class="text-muted p-t-30 db">Phone</small>
                <h6>{{$client->CLNT_MOBN}}</h6>
                <small class="text-muted p-t-30 db">Area</small>
                <h6>{{$client->area->AREA_NAME}}</h6>
                <small class="text-muted p-t-30 db">Address</small>
                <h6>{{$client->CLNT_ADRS}}</h6>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Balance</h5>
                <div class="d-flex m-t-30 m-b-20 no-block align-items-center">
                    <span class="display-5 text-success"><i class=" ti-book"></i></span>
                    <a href="javscript:void(0)" class="link display-5 ml-auto">{{number_format($client->CLNT_BLNC,2)}}</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Paid</h5>
                <div class="d-flex m-t-30 m-b-20 no-block align-items-center">
                    <span class="display-5 text-success"><i class=" ti-money"></i></span>
                    <a href="javscript:void(0)" class="link display-5 ml-auto">{{number_format($clientMoney->paid,2)}}</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-8 col-xlg-9 col-md-7">
        <div class="card">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs profile-tab" role="tablist">
                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#perf" role="tab">Performance</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#history" role="tab">Order History</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#bought" role="tab">Items Bought</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#paid" role="tab">Account</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#pay" role="tab">Add Payment</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab">Settings</a> </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!--Orders tab-->
                <div class="tab-pane active" id="perf" role="tabpanel">
                    <div class="card-body">
                        <h4 class="card-title">Client's Order Performance</h4>
                        <h6 class="card-subtitle">Monthly performance</h6>
                        <div class="col-12">
                            <x-simple-chart :chartTitle="$totalTitle" :chartSubtitle="$totalSubtitle" :graphs="$totalGraphs" :totals="$totalTotals" />
                        </div>
                    </div>
                </div>
                 <!--Orders tab-->
                 <div class="tab-pane" id="history" role="tabpanel">
                    <div class="card-body">
                        <h4 class="card-title">Client's Orders History</h4>
                        <h6 class="card-subtitle">Total Money Paid: {{$clientMoney->paid}}, Discount offered: {{$clientMoney->discount}}</h6>
                        <div class="col-12">
                            <x-datatable id="myTable" :title="$title ?? 'Orders History'" :subtitle="$subTitle ?? ''" :cols="$ordersCols" :items="$orderList" :atts="$orderAtts" :cardTitle="false" />
                        </div>
                    </div>
                </div>


                <!--Item Bought tab-->
                <div class="tab-pane" id="bought" role="tabpanel">
                    <div class="card-body">
                        <div class="col-12">
                            <x-datatable id="myTable4" :title="$title ?? 'Items Bought'" :subtitle="$subTitle ?? ''" :cols="$boughtCols" :items="$boughtList" :atts="$boughtAtts" :cardTitle="false" />
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="paid" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <x-datatable id="myTable3" :title="$payTitle" :subtitle="$paySubtitle" :cols="$payCols" :items="$pays" :atts="$payAtts" />
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="pay" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">{{ $formTitle }}</h4>
                                    <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type=hidden name=id value="{{(isset($client)) ? $client->id : ''}}">

                                        <div class="form-group">
                                            <label>Amount*</label>
                                            <div class="input-group mb-3">
                                                <input type="number" step=0.01 class="form-control" placeholder="Payment Amount" name=amount value="0" required>
                                            </div>
                                            <small class="text-danger">{{$errors->first('amount')}}</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Comment</label>
                                            <div class="input-group mb-3">
                                                <textarea class="form-control" rows="2" name="comment"></textarea>
                                            </div>

                                            <small class="text-danger">{{$errors->first('comment')}}</small>
                                        </div>

                                        <button type="submit" class="btn btn-success mr-2">Add Payment</button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="tab-pane" id="settings" role="tabpanel">
                    <div class="card-body">
                        <h4 class="card-title">Edit {{ $client->CLNT_NAME }}'s Info</h4>
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
                                    <input type="text" class="form-control" placeholder="Client Balance" name=balance value="{{ (isset($client)) ? $client->CLNT_BLNC : old('balance') }}" required>
                                </div>

                                <small class="text-danger">{{$errors->first('balance')}}</small>
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


                            <button type="submit" class="btn btn-success mr-2">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
</div>
@endsection

@section("js_content")
<script type="text/javascript" src="{{asset('assets/node_modules/multiselect/js/jquery.multi-select.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/node_modules/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>


<script src="https://widget.cloudinary.com/v2.0/global/all.js" type="text/javascript"></script>
<script>
    var myWidget = cloudinary.createUploadWidget({
    cloudName: 'sasawhale', 
    folder: "whale/models",
    uploadPreset: 'whalesec'}, (error, result) => { 
      if (!error && result && result.event === "success") { 
        document.getElementById('uploaded').value = result.info.url;
      }
    }
  )
  
  document.getElementById("upload_widget").addEventListener("click", function(){
      myWidget.open();
    }, false);

    function confirmAndGoTo(url, action){
    Swal.fire({
        text: "Are you sure you want to " + action + "?",
        icon: "warning",
        showCancelButton: true,
        }).then((isConfirm) => {
    if(isConfirm.value){

    window.location.href = url;
        }
    });

}

</script>
@endsection
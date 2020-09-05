@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total KGs Supplied</h5>
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
                <h5 class="card-title">Total Paid</h5>
                <div class="d-flex m-t-30 m-b-20 no-block align-items-center">
                    <span class="display-5 text-success"><i class="fas fa-hand-holding-usd"></i></span>
                    <a href="javscript:void(0)" class="link display-5 ml-auto">{{number_format($totalPaid,2)}}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Balance</h5>
                <div class="d-flex m-t-30 m-b-20 no-block align-items-center">
                    <span class="display-5 text-success"><i class="ti-money"></i></span>
                    <a href="javscript:void(0)" class="link display-5 ml-auto">{{number_format($balance,2)}}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Transactions and Payments</h4>
                <h6 class="card-subtitle">Check out all transactions and payments for {{$supplier->SUPP_NAME}}</h6>
                <!-- Nav tabs -->
                <div class="vtabs">
                    <ul class="nav nav-tabs tabs-vertical" role="tablist">
                        <li class="nav-item ">
                            <a class="nav-link active" data-toggle="tab" href="#info" role="tab" aria-selected="false">
                                <span class="hidden-sm-up"><i class="icon-user"></i></span>
                                <span class="hidden-xs-down">Supplier Info</span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" data-toggle="tab" href="#stock" role="tab" aria-selected="false">
                                <span class="hidden-sm-up"><i class="icon-social-dropbox"></i></span>
                                <span class="hidden-xs-down">Related Stock</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#trans" role="tab" aria-selected="false">
                                <span class="hidden-sm-up">
                                    <i class="icon-layers"></i></span>
                                <span class="hidden-xs-down">Entries</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#paid" role="tab" aria-selected="false">
                                <span class="hidden-sm-up">
                                    <i class="icon-book-open"></i></span>
                                <span class="hidden-xs-down">Account</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#pay" role="tab" aria-selected="false">
                                <span class="hidden-sm-up">
                                    <i class="fas fa-hand-holding-usd"></i></span>
                                <span class="hidden-xs-down">Add Payment</span>
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="info" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <small class="text-muted">Supplier Name </small>
                                            <h6>{{$supplier->SUPP_NAME}}</h6>
                                            <small class="text-muted p-t-30 db">Phone</small>
                                            <h6>{{$supplier->SUPP_MOBN}}</h6>
                                            <small class="text-muted p-t-30 db">Balance</small>
                                            <h6>{{$supplier->SUPP_BLNC}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="stock" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <x-datatable id="myTable" :title="$rawTitle" :subtitle="$rawSubtitle" :cols="$rawCols" :items="$raws" :atts="$rawAtts" />
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
                                                <input type=hidden name=id value="{{(isset($supplier)) ? $supplier->id : ''}}">

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

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        {{-- <x-datatable id="myTable" :title="$title" :subtitle="$subTitle" :cols="$cols" :items="$items" :atts="$atts" /> --}}
    </div>
</div>
@endsection
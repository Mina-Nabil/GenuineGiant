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
                <h5 class="card-title">Average EGP/KG</h5>
                <div class="d-flex m-t-30 m-b-20 no-block align-items-center">
                    <span class="display-5 text-warning"><i class="icon-briefcase"></i></span>
                    <a href="javscript:void(0)" class="link display-5 ml-auto">{{number_format($averagePrice,2)}}</a>
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
                <h4 class="card-title">Stock Table</h4>
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
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="stock" role="tabpanel">
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
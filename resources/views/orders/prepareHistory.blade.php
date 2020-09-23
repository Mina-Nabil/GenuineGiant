@extends('layouts.app')

@section('content')

<script>
    function goToHistory(){
    let monthSel = document.getElementById('month');
    let yearSel = document.getElementById('year');

    let selectedMonth   = monthSel.options[monthSel.selectedIndex].value;
    let selectedYear    = yearSel.options[yearSel.selectedIndex].value;

    window.location.href = '{{url('orders/history')}}' + '/' + selectedYear + '/' + selectedMonth + '/-1';

}
</script>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Old Orders</h4>
                <h6 class="card-subtitle">Load Previous Order Data</h6>

                <div class="form-group">
                    <label>Year</label>
                    <div class="input-group mb-3">
                        <select id=year class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                            @foreach ($years as $year)
                            <option value="{{$year->order_year}}">{{$year->order_year}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Month</label>
                    <div class="input-group mb-3">
                        <select id=month class="select2 form-control custom-select" style="width: 100%; height:36px;">
                            <option value="-1" selected>All Months</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <small class="text-danger">{{$errors->first('month')}}</small>
                </div>

                <button onclick="goToHistory()" class="btn btn-success mr-2">Submit</button>


            </div>
        </div>
    </div>

</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $formTitle }}</h4>
                <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
                    @csrf



                    <?php $i=1;
                        $max = count($ingredients) ; ?>
                    @foreach ($ingredients as $rawID => $rawKG)
                    @if ($i == $max)
                    <div class="row ">
                    @else
                        <div class="row removeclass{{$i}}">
                    @endif
                            <div class="col-lg-8">
                                <div class="input-group mb-2">
                                    <select name=raw[] class="form-control select2  custom-select" required>
                                        <option disabled hidden selected value="">Products</option>
                                        @foreach($raws as $item)
                                        <option value="{{ $item->id }}" @if ($item->id == $rawID)
                                            selected
                                            @endif
                                            >
                                            {{$item->RWMT_NAME}} - {{$item->RWMT_ARBC_NAME}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group mb-3">
                                    <input type="number" step=0.01 class="form-control amount" placeholder="KGs" name=count[] aria-describedby="basic-addon11" value={{$rawKG}} required>
                                    <div class="input-group-append">
                                        @if ($i == $max)
                                        <button class="btn btn-success" id="dynamicAddButton" type="button" onclick="addToab();"><i class="fa fa-plus"></i></button>
                                        @else
                                        <button class="btn btn-danger" id="dynamicAddButton" type="button" onclick="removeToab({{$i}});"><i class="fa fa-minus"></i></button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <?php $i++; ?>
                        </div>
                  
                    
                        @endforeach
                        <hr>
                        <div id="dynamicContainer" class="nopadding row ">
                        </div>

                        <button type="submit" class="btn btn-success mr-2">Submit</button>
                        <button type="button" class="btn btn-dark mr-2" onclick="confirmAndGoTo('{{$skipURL}}', 'Skip Consuming Raw Materials')">Skip</button>
                </form>
            </div>
        </div>
    </div>

</div>


<script>

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


    var room = {{$i}};
   function addToab() {
   
   room++;
   var objTo = document.getElementById('dynamicContainer')
   var divtest = document.createElement("div");
   divtest.setAttribute("class", "nopadding row col-lg-12 removeclass" + room);
   var rdiv = 'removeclass' + room;
   var concatString = "";
   concatString +=   '<div class="col-lg-8">\
                            <div class="input-group mb-2">\
                                <select name=raw[] class="form-control select2  custom-select" required>\
                                    <option disabled hidden selected value="">Products</option>\
                                    @foreach($raws as $item)\
                                    <option value="{{ $item->id }}" @if ($item->id == $rawID)\
                                        selected\
                                        @endif\
                                        >\
                                        {{$item->RWMT_NAME}} - {{$item->RWMT_ARBC_NAME}} </option>\
                                    @endforeach\
                                </select>\
                            </div></div>';

   
   concatString +=     " <div class='col-lg-4'>\
                            <div class='input-group mb-3'>\
                                   <input type='number' step=1 class='form-control amount' placeholder='KGs'\
                                       name=count[] \
                                       aria-describedby='basic-addon11' required>\
                                   <div class='input-group-append'>\
                                    <button class='btn btn-danger' type='button' onclick='removeToab(" + room + ");'><i class='fa fa-minus'></i></button>\
                                   </div>\
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
@extends('layouts.simple')

@section('css_before')
    <link rel="stylesheet" href="{{asset('js/plugins/ion-rangeslider/css/ion.rangeSlider.css')}}">
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="block-title">
                <div class="block-content text-center">
                    <h1>Pornstars</h1>
                </div>
            </div>
        </div>

        <div class="box">
            <input type="text" class="js-range-slider" name="my_range" value="" />


        </div>

        <div class="block">
            <div class="block-content block-content-full">
                <button type="button" class="btn btn-alt-info" data-toggle="modal" data-target="#age">Age</button>
            </div>
        </div>

        <div class="modal fade" id="age" tabindex="-1" role="dialog" aria-labelledby="modal-fadein" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Range Age</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <input type="text" class="js-rangeslider" id="example-rangeslider4" name="example-rangeslider4" data-type="double" data-grid="true" data-min="18" data-max="90" data-from="18" data-to="90" onchange="myfunc()">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-alt-secondary" data-dismiss="modal" onclick="closeAgeFunction()">Close</button>
                        <button type="button" class="btn btn-alt-success" data-dismiss="modal">
                            <i class="fa fa-check"></i> Perfect
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div class="block">
            <div class="block-content">
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label mt-10">Double</label>
                    <div class="col-lg-10">
                        <input type="text" class="js-rangeslider" id="x-rangeslider4" name="example-rangeslider4" data-type="double" data-grid="true" data-min="18" data-max="90" data-from="18" data-to="90" onchange="myfunc()">
                    </div>
                </div>
            </div>
        </div>
        @foreach($all_pornstars as $pornstar)
            <div class="block">
                <hr>
                <div class="block-content block-content-full">
                    <div class="row align-items-center">
                        <div class="col-sm-6 py-10">
                            <h3 class="h5 font-w700 mb-10">
                                <img class="img-avatar img-avatar96 img-avatar-thumb" src="{{$pornstar->link_img}}" alt=""> {{$pornstar->full_name}}
                            </h3>
                            @if($pornstar->available == 0)
                                <p class="font-size-sm text-muted mb-0">
                                    Not Available
                                </p>
                            @else

                                <p class="font-size-sm text-muted mb-0">
                                    Age: {{$pornstar->age}}
                                </p>
                                <p class="font-size-sm mb-10">

                                    @if($pornstar->modelHub != NULL)
                                        <a class="mr-5 mb-5" href="{{$pornstar->modelHub}}">ModelHub</a>
                                    @endif

                                    @if($pornstar->official_site != NULL)
                                        <a class="mr-5 mb-5" href="{{$pornstar->official_site}}">Official site</a>
                                    @endif

                                    @if($pornstar->instagram != NULL)
                                        <a class="mr-5 mb-5" href="{{$pornstar->instagram}}">Instagram</a>
                                    @endif

                                    @if($pornstar->twitter != NULL)
                                        <a class="mr-5 mb-5" href="{{$pornstar->twitter}}">Twitter</a>
                                    @endif

                                    @if($pornstar->fan_centro != NULL)
                                        <a class="mr-5 mb-5" href="{{$pornstar->fan_centro}}">Fan Centro</a>
                                    @endif


                                </p>
                            @endif
                        </div>
                        <div class="col-sm-6 py-10 text-md-right">
                            <a class="btn btn-sm btn-outline-primary btn-rounded mr-5 my-5" href="javascript:void(0)">
                                <i class="fa fa-wrench mr-1"></i> More Info
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
@section('js_after')
    <script src="{{asset('js/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
    <script>jQuery(function(){ Codebase.helpers(['rangeslider']); });</script>

    <script>
        function myfunc(){
            // console.log(document.getElementById('example-rangeslider4').value);
        }

        function closeAgeFunction(){

            $("#example-rangeslider4").ionRangeSlider({
                from: 18,
                to: 80,
            });
            // console.log('prov');

        }


        var $d3 = $("#demo_3");

        $d3.ionRangeSlider({
            skin: "big",
            min: 0,
            max: 10000,
            from: 5000
        });

        $d3.on("change", function () {
            var $inp = $(this);
            var from = $inp.prop("value"); // reading input value
            var from2 = $inp.data("from"); // reading input data-from attribute

            console.log(from, from2); // FROM value
        });

    </script>
@endsection

@extends('layouts.simple')

@section('css_before')
    <link rel="stylesheet" href="{{asset('js/plugins/ion-rangeslider/css/ion.rangeSlider.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/flatpickr/flatpickr.min.css')}}">
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
        <div class="block">
            <div class="block-content">
                <form method="POST" action="{{route('pornstar_get')}}" id="search_form" onsubmit="false">
                    @csrf
                    <div class="row">
                        Filters:
                    </div>
                    <div class="row">
                        <div class="push">
                            <div class="btn-group" role="group" aria-label="btnGroup2">
                                <div class="btn-group" role="group" id="type_dropdown">
                                    <button type="button" class="btn btn-info dropdown-toggle" id="type_dropdown_menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Type</button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                        <div class="dropdown-item">
                                            <label class="css-control css-control-primary css-checkbox">
                                                <input type="checkbox" class="css-control-input" @if(request()->input('pornstar') == 'on') checked @endif name="pornstar">
                                                <span class="css-control-indicator"></span> Pornstar
                                            </label>
                                        </div>
                                        <div class="dropdown-item">
                                            <label class="css-control css-control-primary css-checkbox">
                                                <input type="checkbox" class="css-control-input" @if(request()->input('amateur_model') == 'on') checked @endif name="amateur_model">
                                                <span class="css-control-indicator"></span> Amateur model
                                            </label>
                                        </div>

                                    </div>
                                </div>

                                <button type="button" data-toggle="modal" data-target="#age" class="btn btn-info">Range Age: <i id="more"> @if(request()->input('more_than_age') != NULL) {{request()->input('more_than_age')}} @else 18 @endif </i> - <i id="less"> @if(request()->input('less_than_age') != NULL) {{request()->input('less_than_age')}} @else 90 @endif</i></button>
                                <div class="modal fade" id="age" tabindex="-1" role="dialog" aria-labelledby="modal-fadein" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="block block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark text-center">
                                                    <h3 class="block-title">Range Age</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                            <i class="si si-close"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="block-content text-center">
                                                    <div class="row">
                                                        <div class="col-6 text-center">
                                                            <div class="col-8 mx-auto">
                                                                <div class="form-material">

                                                                    <input type="number" min="18" max="90" @if(request()->input('more_than_age') != NULL) value="{{request()->input('more_than_age')}}" @else value="18" @endif class="form-control" id="more_than_age" name="more_than_age" placeholder="..." onchange="moreThanAge()">
                                                                    <label for="more_than_age">Maggiore di</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6 text-center">

                                                            <div class="col-8 mx-auto">
                                                                <div class="form-material">
                                                                    <input type="number" min="18" max="90" @if(request()->input('less_than_age') != NULL) value="{{request()->input('less_than_age')}}" @else value="90" @endif class="form-control" id="less_than_age" name="less_than_age" placeholder="..." onchange="lessThanAge()">
                                                                    <label for="less_than_age">Minore di</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
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


                                <div class="btn-group" role="group" id="verified_dropdown">
                                    <button type="button" class="btn btn-info dropdown-toggle" id="verified_dropdown_menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Verified</button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                        <div class="dropdown-item">
                                            <label class="css-control css-control-primary css-checkbox">
                                                <input type="checkbox" class="css-control-input" @if(request()->input('verified') == 'on') checked @endif name="verified">
                                                <span class="css-control-indicator"></span> Verified
                                            </label>
                                        </div>
                                        <div class="dropdown-item">
                                            <label class="css-control css-control-primary css-checkbox">
                                                <input type="checkbox" class="css-control-input" @if(request()->input('not_verified') == 'on') checked @endif name="not_verified">
                                                <span class="css-control-indicator"></span> Not Verified
                                            </label>
                                        </div>

                                    </div>
                                </div>


                                <button type="button" data-toggle="modal" data-target="#joined_date" class="btn btn-info">Joined date</button>
                                <div class="modal fade" id="joined_date" tabindex="-1" role="dialog" aria-labelledby="modal-fadein" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="block block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark text-center">
                                                    <h3 class="block-title">Joined date</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                            <i class="si si-close"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="block-content">
                                                    <div class="form-group col-8 mx-auto">
                                                        <div class="form-material">
                                                            <input type="text" class="js-flatpickr form-control bg-white text-center" id="joined_date" name="joined_date" @if(request()->input('joined_date') != NULL) value="{{request()->input('joined_date')}}" placeholder="{{request()->input('joined_date')}}" @else  placeholder="Y-m-d" @endif>
                                                        </div>
                                                    </div>
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

                                <div class="btn-group" role="group" id="socials_dropdown">
                                    <button type="button" class="btn btn-info dropdown-toggle" id="socials_dropdown_menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Socials</button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                        <div class="dropdown-item">
                                            <label class="css-control css-control-primary css-checkbox">
                                                <input type="checkbox" class="css-control-input" @if(request()->input('modelhub') == 'on') checked @endif name="modelhub">
                                                <span class="css-control-indicator"></span> ModelHub
                                            </label>
                                        </div>
                                        <div class="dropdown-item">
                                            <label class="css-control css-control-primary css-checkbox">
                                                <input type="checkbox" class="css-control-input" @if(request()->input('website') == 'on' ) checked @endif name="website">
                                                <span class="css-control-indicator"></span> Website <i class="fa fa-desktop"></i>
                                            </label>
                                        </div>
                                        <div class="dropdown-item">
                                            <label class="css-control css-control-primary css-checkbox">
                                                <input type="checkbox" class="css-control-input" @if(request()->input('instagram') == 'on') checked @endif name="instagram">
                                                <span class="css-control-indicator"></span> Instagram <i class="fa fa-instagram"></i>
                                            </label>
                                        </div>
                                        <div class="dropdown-item">
                                            <label class="css-control css-control-primary css-checkbox">
                                                <input type="checkbox" class="css-control-input" @if(request()->input('twitter') == 'on') checked @endif name="twitter">
                                                <span class="css-control-indicator"></span> Twitter <i class="fa fa-twitter"></i>
                                            </label>
                                        </div>
                                        <div class="dropdown-item">
                                            <label class="css-control css-control-primary css-checkbox">
                                                <input type="checkbox" class="css-control-input" @if(request()->input('fan_centro') == 'on') checked @endif name="fan_centro">
                                                <span class="css-control-indicator"></span> Fan_centro
                                            </label>
                                        </div>


                                    </div>

                                </div>
                                <div class="btn-group" role="group" id="videos_dropdown">
                                    <button type="button" class="btn btn-info dropdown-toggle" id="videos_dropdown_menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Videos</button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                        <div class="dropdown-item">
                                            <div class="form-material">
                                                <input type="number" min="0" name="more_than_video" class="form-control" @if(request()->input('more_than_video') != NULL) value="{{request()->input('more_than_video')}}" @endif>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="btn-group" role="group" id="visuals_dropdown">
                                    <button type="button" class="btn btn-info dropdown-toggle" id="visuals_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Visuals</button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                        <div class="dropdown-item">
                                            <div class="form-material">
                                                <input type="number" min="0" name="more_than_visual" class="form-control" @if(request()->input('more_than_visual') != NULL) value="{{request()->input('more_than_visual')}}" @endif>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="btn-group" role="group" id="subscriber_dropdown">
                                    <button type="button" class="btn btn-info dropdown-toggle" id="subscriber_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Subscribers</button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                        <div class="dropdown-item">
                                            <div class="form-material">
                                                <input type="number" min="0" name="more_than_subscriber" class="form-control" @if(request()->input('more_than_subscriber') != NULL) value="{{request()->input('more_than_subscriber')}}" @endif>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" id="btnGroupDrop1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                        <ul class="nav-main">
                                            <li>
                                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-trophy"></i><span class="sidebar-mini-hide">Components</span></a>
                                                <ul>
                                                    <li>
                                                        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><span class="sidebar-mini-hide">Main Menu</span></a>
                                                        <ul>
                                                            <li>
                                                                <a href="#">Link 1-1</a>
                                                            </li>
                                                            <li>
                                                                <a href="#">Link 1-2</a>
                                                            </li>
                                                            <li>
                                                                <a class="nav-submenu" data-toggle="nav-submenu" href="#">Sub Level 2</a>
                                                                <ul>
                                                                    <li>
                                                                        <a href="#">Link 2-1</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#">Link 2-2</a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">Sub Level 3</a>
                                                                        <ul>
                                                                            <li>
                                                                                <a href="#">Link 3-1</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="#">Link 3-2</a>
                                                                            </li>
                                                                        </ul>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-info">Right</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        Order by:
                    </div>

                    {{--                                <label> Order by age</label>--}}
                    {{--                                <br>--}}
                    {{--                                <label class="css-control css-control-primary css-radio">--}}
                    {{--                                    <input type="radio" class="css-control-input" name="order_by">--}}
                    {{--                                    <span class="css-control-indicator"></span> Ascending--}}
                    {{--                                </label>--}}
                    {{--                                <br>--}}
                    {{--                                <label class="css-control css-control-primary css-radio">--}}
                    {{--                                    <input type="radio" class="css-control-input" name="order_by">--}}
                    {{--                                    <span class="css-control-indicator"></span> Decreasing--}}
                    {{--                                </label>--}}






                    <div class="row">
                        <div class="push">

                            <div class="btn-group" role="group" id="order_by_age_btn">
                                <button type="button" class="btn btn-info dropdown-toggle" id="order_by_age_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Age</button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                    <div class="dropdown-item">

                                        <label> Order by age</label>
                                        <br>
                                        <label class="css-control css-control-primary css-radio">
                                            <input type="radio" class="css-control-input" name="order_by" value="info_age_ASC">
                                            <span class="css-control-indicator"></span> Ascending
                                        </label>
                                        <br>
                                        <label class="css-control css-control-primary css-radio">
                                            <input type="radio" class="css-control-input" name="order_by" value="info_age_DESC">
                                            <span class="css-control-indicator"></span> Decreasing
                                        </label>
                                    </div>


                                </div>

                            </div>

                            <div class="btn-group" role="group" id="order_by_video_btn">
                                <button type="button" class="btn btn-info dropdown-toggle" id="order_by_video_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Videos</button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                    <div class="dropdown-item">

                                        <label> Order by videos</label>
                                        <br>
                                        <label class="css-control css-control-primary css-radio">
                                            <input type="radio" class="css-control-input" name="order_by" value="rank_video_ASC">
                                            <span class="css-control-indicator"></span> Ascending
                                        </label>
                                        <br>
                                        <label class="css-control css-control-primary css-radio">
                                            <input type="radio" class="css-control-input" name="order_by" value="rank_video_DESC">
                                            <span class="css-control-indicator"></span> Decreasing
                                        </label>
                                    </div>


                                </div>
                            </div>

                            <div class="btn-group" role="group" id="order_by_subscriber_btn">
                                <button type="button" class="btn btn-info dropdown-toggle" id="order_by_subscriber_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Subscribers</button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                    <div class="dropdown-item">

                                        <label> Order by subscribers</label>
                                        <br>
                                        <label class="css-control css-control-primary css-radio">
                                            <input type="radio" class="css-control-input" name="order_by" value="rank_subscriber_ASC">
                                            <span class="css-control-indicator"></span> Ascending
                                        </label>
                                        <br>
                                        <label class="css-control css-control-primary css-radio">
                                            <input type="radio" class="css-control-input" name="order_by" value="rank_subscriber_DESC">
                                            <span class="css-control-indicator"></span> Decreasing
                                        </label>
                                    </div>


                                </div>
                            </div>

                            <div class="btn-group" role="group" id="order_by_joined_date_btn">
                                <button type="button" class="btn btn-info dropdown-toggle" id="order_by_joined_date_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Joined date</button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                    <div class="dropdown-item">

                                        <label> Order by joined date</label>
                                        <br>
                                        <label class="css-control css-control-primary css-radio">
                                            <input type="radio" class="css-control-input" name="order_by" value="info_joined_date_ASC">
                                            <span class="css-control-indicator"></span> Ascending
                                        </label>
                                        <br>
                                        <label class="css-control css-control-primary css-radio">
                                            <input type="radio" class="css-control-input" name="order_by" value="info_joined_date_DESC">
                                            <span class="css-control-indicator"></span> Decreasing
                                        </label>
                                    </div>


                                </div>
                            </div>

                            <div class="btn-group" role="group" id="order_by_rank_btn">
                                <button type="button" class="btn btn-info dropdown-toggle" id="order_by_rank_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ranks</button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                    <div class="dropdown-item">

                                        <label> Order by rank</label>
                                    </div>

                                    <div class="dropdown-item">
                                        <div class="form-material">
                                            <select class="form-control" id="which_rank" name="which_rank" onchange="changeValueRank()">
                                                <option value="weekly" selected >Weekly</option>
                                                <option value="monthly">Monthly</option>
                                                <option value="last_month">Last Month</option>
                                                <option value="yearly">Yearly</option>
                                            </select>
                                        </div>
                                        <label class="css-control css-control-primary css-radio">
                                            <input type="radio" class="css-control-input" name="order_by" value="rank_weekly_ASC" id="asc_rank">
                                            <span class="css-control-indicator"></span> Ascending
                                        </label>
                                        <br>
                                        <label class="css-control css-control-primary css-radio">
                                            <input type="radio" class="css-control-input" name="order_by" value="rank_weekly_DESC" id="desc_rank">
                                            <span class="css-control-indicator"></span> Decreasing
                                        </label>
                                        <hr>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>








                    <div class="row text-center">
                        <div class="col-4">
                        </div>
                        <div class="col-4">


                        </div>
                        <div class="col-4">

                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-sm-6 py-10">
                        </div>
                        <div class="col-sm-6 py-10 text-md-right">
                            <button type="submit" class="btn btn-alt-primary min-width-125">Search</button>
                </form>
                <br>
                <br>
                <form method="POST" action="{{route('pornstar_get')}}">
                    <button type="submit" class="btn btn-alt-warning min-width-125">Reset</button>
                </form>
            </div>

        </div>


    </div>

    </div>


    @foreach($all_pornstars as $pornstar)
        @php
            $date['from'] = '2020-09-06';
            $date['to'] = '2020-10-07';
            $increase = getIncreases($pornstar->id, $date);


        @endphp
        <div class="block">
            <hr>
            <div class="block-content block-content-sm">
                <div class="row align-items-center">
                    <div class="mr-auto">
                        <h4><small>Weekly Rank:</small> <b>{{$pornstar->weekly}}</b>   |   <small>Monthly Rank:</small> <b>{{$pornstar->monthly}}</b>   |   <small>Last month Rank:</small> <b>{{$pornstar->last_month}}</b>   |   <small>Yearly Rank:</small> <b>{{$pornstar->yearly}}</b></h4>
                    </div>
                    <h4>{{$pornstar->type}}</h4>
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

                                @if($pornstar->modelhub != NULL)
                                    <a class="mr-5 mb-5" href="{{$pornstar->modelhub}}">ModelHub</a>
                                @endif

                                @if($pornstar->website != NULL)
                                    <a class="mr-5 mb-5" href="{{$pornstar->website}}">Official site</a>
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
                        <br>
                        <br>
                        <a class="btn btn-sm btn-outline-primary btn-rounded mr-5 my-5" href="javascript:void(0)">
                            <i class="fa fa-wrench mr-1"></i> More Info
                        </a>
                        <br>
                        <br>
                        <br>
                        <p>
                            Subsribers: {{$pornstar->subscriber}}
                            <br>
                            Visuals: {{$pornstar->visual}}
                            <br>
                            Videos: {{$pornstar->video}}
                            <br>
                            Average Subscribers/Videos: {{getAverage($pornstar->video, $pornstar->visual)}}
                            <br>
                            Increase visuals: {{$increase['visuals']}}%
                            <br>
                            Increase subscribers: {{$increase['subscribers']}}%

                        </p>
                    </div>

                </div>
            </div>
        </div>


        @endforeach
        </div>

@endsection
@section('js_after')
    <script src="{{asset('js/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
    <script>jQuery(function(){ Codebase.helpers(['rangeslider', 'flatpickr']); });</script>
    <script src="{{asset('js/plugins/flatpickr/flatpickr.min.js')}}"></script>

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

        function changeValueRank(){
            document.getElementById('asc_rank').value = 'rank_' + document.getElementById('which_rank').value + '_ASC';
            document.getElementById('desc_rank').value = 'rank_' + document.getElementById('which_rank').value + '_DESC';
        }


        function moreThanAge(){
            if(document.getElementById('less_than_age').value < document.getElementById('more_than_age').value){
                document.getElementById('more_than_age').value = document.getElementById('less_than_age').value;
            }

            if(document.getElementById('more_than_age').value < '18'){
                document.getElementById('more_than_age').value = '18';
            }
            document.getElementById('less_than_age').setAttribute('min', document.getElementById('more_than_age').value);
            document.getElementById('more').innerHTML = document.getElementById('more_than_age').value;
        }

        function lessThanAge(){

            if(document.getElementById('less_than_age').value < document.getElementById('more_than_age').value){
                document.getElementById('less_than_age').value = document.getElementById('more_than_age').value;
            }
            document.getElementById('less').innerHTML = document.getElementById('less_than_age').value;
            document.getElementById('more_than_age').setAttribute('max', document.getElementById('less_than_age').value);

        }

        $('.dropdown-menu').on({
            "click":function(e){
                e.stopPropagation();
            }
        });

        function ajaxCall(){
            var form_data = new FormData(document.getElementById('search_form'));
            form_data.append('orberby', 'asc');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : "{{route('pornstar_get')}}",
                type: "POST",
                data: form_data,
                dataType: 'json',
                cache : false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data)
                },
                fail: function () {
                    console.log('fail')
                }

            });

        }

    </script>
@endsection

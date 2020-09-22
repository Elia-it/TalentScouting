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
                                                    <label>More than videos</label>
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
                                                    <label>More than visuals</label>
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
                                                    <labelMore than <br>subscribers</label><br>
                                                    <input type="number" min="0" name="more_than_subscriber" class="form-control" @if(request()->input('more_than_subscriber') != NULL) value="{{request()->input('more_than_subscriber')}}" @endif>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            Order by:
                        </div>
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
                                                <input type="radio" class="css-control-input" name="order_by" value="rank_videos_ASC">
                                                <span class="css-control-indicator"></span> Ascending
                                            </label>
                                            <br>
                                            <label class="css-control css-control-primary css-radio">
                                                <input type="radio" class="css-control-input" name="order_by" value="rank_videos_DESC">
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
                                                <input type="radio" class="css-control-input" name="order_by" value="rank_subscribers_ASC">
                                                <span class="css-control-indicator"></span> Ascending
                                            </label>
                                            <br>
                                            <label class="css-control css-control-primary css-radio">
                                                <input type="radio" class="css-control-input" name="order_by" value="rank_subscribers_DESC">
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

                        <div class="row">
                            Increase by:
                        </div>

                        <div class="row">
                            <div class="push">
                                <button type="button" data-toggle="modal" data-target="#increase" class="btn btn-info">
                                    <label id="p_increase_from">@if(request()->input('increase_from')) {{request()->input('increase_from')}} @else 2019-01-01 @endif</label>
                                    to
                                    <label id="p_increase_to"> @if(request()->input('increase_to')) {{request()->input('increase_to')}} @else {{date('Y-m-d')}} @endif</label>


                                </button>
                                <div class="modal fade" id="increase" tabindex="-1" role="dialog" aria-labelledby="modal-fadein" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="block block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark text-center">
                                                    <h3 class="block-title">Increase from date</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                            <i class="si si-close"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="block-content text-center">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="col-12">
                                                                <label for="increase_from">Date from</label>
                                                                <div class="form-material">
                                                                    <input type="text" class="js-flatpickr form-control bg-white text-center" id="increase_from" name="increase_from" @if(request()->input('increase_from') != NULL) value="{{request()->input('increase_from')}}" placeholder="{{request()->input('increase_form')}}" @else  value="2019-01-01"  @endif placeholder="Y-m-d" onchange="increaseFromFunction()">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="col-12">
                                                                <label for="increase_from">Date to</label>
                                                                <div class="form-material">
                                                                    <input type="text" class="js-flatpickr form-control bg-white text-center" id="increase_to" name="increase_to" @if(request()->input('increase_to') != NULL) value="{{request()->input('increase_to')}}" placeholder="{{request()->input('increase_to')}}" @else  value="{{date('Y-m-d')}}" @endif placeholder="Y-m-d" onchange="increaseToFunction()">
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

    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                Pornstars @if(request()->input('order_by'))<small>order by {{request()->input('order_by')}}</small> @endif
            </h3>
        </div>
        <div class="block-content">
            <table class="js-table-sections table table-hover">
                <thead>
                <tr>
                    <th style="width: 30px;"></th>
                    <th>Image</th>
                    <th>Name</th>
                    <th style="width: 15%;">Type</th>
                    <th class="d-none d-sm-table-cell" style="width: 20%;">Joined date</th>
                </tr>
                </thead>
                @foreach($all_pornstars as $pornstar)
                    @php
                    $pornController = new \App\Http\Controllers\PornstarController();
                    $dates = $pornController->getDataForIncreases($pornstar->id, $date_increase);
                    $increase = getIncreases($dates);
                    @endphp

                    <tbody class="js-table-sections-header">
                    <tr>
                        <td class="text-center">
                            <i class="fa fa-angle-right"></i>
                        </td>
                        <td>
                            <img class="img-avatar img-avatar96 img-avatar-thumb" alt="" src="{{$pornstar->link_img}}" >
                        </td>
                        <td class="font-w600">
                            {{$pornstar->full_name}}
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



                        </td>
                        <td class="font-w600">{{$pornstar->type}}</td>
                        <td class="d-none d-sm-table-cell">
                            <em class="text-muted">{{$pornstar->joined_date}}</em>
                        </td>
                    </tr>
                    </tbody>
                    <tbody>
                    <tr>
                        <td class="text-center"></td>
                        <td class="font-w600">Average Visual/video</td>
                        <td class="font-w600">{{getAverageVideo_Visuals($pornstar->videos, $pornstar->visuals)}} video {{$pornstar->videos}} Visual {{$pornstar->visuals}}</td>
                        <td class="font-size-sm"></td>
                        <td class="d-none d-sm-table-cell"></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="font-w600">Increase video</td>
                        <td class="font-w600 @if($increase['videos'] >= 0) text-success">+ @else text-danger"> @endif{{$increase['videos']}} %</td>
                        <td class="font-size-sm">From {{$date_increase['from']}}</td>
                        <td class="font-size-sm">To {{$date_increase['to']}}</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="font-w600">Increase subscriber</td>
                        <td class="font-w600 @if($increase['subscribers'] >= 0) text-success">+ @else text-danger"> @endif{{$increase['subscribers']}} %</td>
                        <td class="font-size-sm">From {{$date_increase['from']}}</td>
                        <td class="font-size-sm">To {{$date_increase['to']}}</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="font-w600">Increase visual</td>
                        <td class="font-w600 @if($increase['visuals'] >= 0) text-success">+ @else text-danger"> @endif{{$increase['visuals']}} %</td>
                        <td class="font-size-sm">From {{$date_increase['from']}}</td>
                        <td class="font-size-sm">To {{$date_increase['to']}}</td>
                    </tr>
{{--                    <tr>--}}
{{--                        <td class="text-center"></td>--}}
{{--                        <td class="font-w600 text-success">+ $120,00</td>--}}
{{--                        <td class="font-size-sm">Stripe</td>--}}
{{--                        <td class="d-none d-sm-table-cell">--}}
{{--                            <span class="font-size-sm text-muted">October 16, 2017 12:16</span>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
                    </tbody>

                @endforeach
            </table>
        </div>


    </div>
    <div class="row">
        <div class="mr-auto">
            <div class="form-material floating" style="bottom: 25px; left: 15px">
                <input type="text" class="form-control" id="go_to_page_in" name="go_to_page_in" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  change="goToPageFunction()">
                <label for="go_to_page_in">Go to page</label>
            </div>
        </div>
        <div class="ml-auto">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-lg">
                    <li class="page-item">
                        <a class="page-link" href="@if(isset($_GET['page']) AND $_GET['page'] != 1) ?page={{$_GET['page'] - 1}} @else ?page=1 @endif" aria-label="Previous">
                                                    <span aria-hidden="true">
                                                        <i class="fa fa-angle-left"></i>
                                                    </span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>

                    @if(!isset($_GET['page']) OR $_GET['page'] == 1)
                        <li class="page-item active">
                            <a class="page-link" href="?page=1">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?page=2">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" disabled="">...</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?page={{$pages}}">{{$pages}}</a>
                        </li>
                    @elseif(isset($_GET['page']))
                        @if($_GET['page'] < 3)
                            <li class="page-item">
                                <a class="page-link" href="?page={{$_GET['page'] - 1}}">{{$_GET['page'] - 1}}</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="?page={{$_GET['page']}}">{{$_GET['page']}}</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="?page={{$_GET['page'] + 1}}">{{$_GET['page'] + 1}}</a>
                            </li>
                        @elseif($_GET['page'] >= 3 AND $_GET['page'] <= $pages - 2)
                            <li class="page-item">
                                <a class="page-link" href="?page=1">1</a>
                            </li>
                             @if($_GET['page'] >3)
                                <li class="page-item">
                                    <a class="page-link" disabled="">...</a>
                                </li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="?page={{$_GET['page'] - 1}}">{{$_GET['page'] - 1}}</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="?page={{$_GET['page']}}">{{$_GET['page']}}</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="?page={{$_GET['page'] + 1}}">{{$_GET['page'] + 1}}</a>
                            </li>
                            @if($_GET['page'] < $pages - 2)
                                <li class="page-item">
                                    <a class="page-link" disabled="">...</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="?page={{$pages}}">{{$pages}}</a>
                                </li>
                            @endif

                        @elseif($_GET['page'] > $pages - 2)
                            <li class="page-item">
                                <a class="page-link" href="?page=1">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" disabled="">...</a>
                            </li>
                            @if($_GET['page'] == $pages)
                                <li class="page-item">
                                    <a class="page-link" href="?page={{$_GET['page'] - 1}}">{{$_GET['page'] - 1}}</a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link" href="?page={{$_GET['page']}}">{{$_GET['page']}}</a>
                                </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="?page={{$_GET['page'] - 1}}">{{$_GET['page'] - 1}}</a>
                            </li>
                                <li class="page-item active">
                                    <a class="page-link" href="?page={{$_GET['page']}}">{{$_GET['page']}}</a>
                                </li>
                            <li class="page-item">
                                <a class="page-link" href="?page={{$_GET['page'] + 1}}">{{$_GET['page'] + 1}}</a>
                            </li>
                            @endif

                        @endif

                    @endif
                    <li class="page-item">
                        <a class="page-link" href="@if( isset($_GET['page'])) ?page={{$_GET['page'] + 1}} @endif" aria-label="Next">
                                                    <span aria-hidden="true">
                                                        <i class="fa fa-angle-right"></i>
                                                    </span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

@endsection
@section('js_after')
    <script src="{{asset('js/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
    <script>jQuery(function(){ Codebase.helpers(['rangeslider', 'flatpickr', 'table-tools']); });</script>
    <script src="{{asset('js/plugins/flatpickr/flatpickr.min.js')}}"></script>

    <script>



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

        function increaseFromFunction(){
            console.log('ciao');
            if(document.getElementById('increase_to').value < document.getElementById('increase_from').value){
                document.getElementById('increase_from').value = document.getElementById('increase_to').value;
            }
            document.getElementById('p_increase_from').innerHTML = document.getElementById('increase_from').value;
        }

        function increaseToFunction(){
            if(document.getElementById('increase_from').value > document.getElementById('increase_to').value){
                document.getElementById('increase_to').value = document.getElementById('increase_from').value;
            }
            document.getElementById('p_increase_to').innerHTML = document.getElementById('increase_to').value;
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

        $(document).ready(function (){
            const go_to_page = document.getElementById('go_to_page_in');
            go_to_page.addEventListener('keypress', function (event){
                if(event.key === "Enter"){
                    location.replace('?page='+document.getElementById('go_to_page_in').value);
                }
            })
        })


    </script>
@endsection

@extends('layouts.simple')

@section('content')

    <div class="container">
        <div class="block block-rounded">
            <div class="block-content block-content-full bg-pattern">
                <div class="py-20 text-center">
                    <h2 class="font-w700 text-black mb-10">
                        All Models
                    </h2>
                </div>
            </div>
        </div>
        <form action="" method="GET">
            <div class="row">
                <div class="col-4 ml-auto">
                    <div class="form-material floating open" style="margin-bottom: 10px">
                        <select class="form-control" id="rows_for_page" name="rows_for_page" onchange="getPages()">
                            <option selected value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="rows_for_page">Model for page</label>
                    </div>
                </div>
            </div>
        </form>


        @foreach($all_pornstars as $pornstar)
            <div class="block">
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

                                    @if($model->modelHub != NULL)
                                        <a class="mr-5 mb-5" href="{{$model->modelHub}}">ModelHub</a>
                                    @endif

                                    @if($model->official_site != NULL)
                                        <a class="mr-5 mb-5" href="{{$model->official_site}}">Official site</a>
                                    @endif

                                    @if($model->instagram != NULL)
                                        <a class="mr-5 mb-5" href="{{$model->instagram}}">Instagram</a>
                                    @endif

                                    @if($model->twitter != NULL)
                                        <a class="mr-5 mb-5" href="{{$model->twitter}}">Twitter</a>
                                    @endif

                                    @if($model->fan_centro != NULL)
                                        <a class="mr-5 mb-5" href="{{$model->fan_centro}}">Fan Centro</a>
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

@endsection

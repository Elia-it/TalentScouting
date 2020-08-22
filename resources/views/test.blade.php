@extends('layouts.simple')

@section('content')
    @foreach($all_models as $model)

        @if($model['available'] == 'yes')

            <img src="{{$model['link_img']}}">
            <h3>{{$model['modelName']}} @if(isset($model['last_name'])) {{$model['last_name']}}@endif</h3>
            Age:{{$model['age']}};
            <br>
            Birth date: {{$model['birth_date']}};
            <br>
            Modelhub site:{{$model['modelHub']}}
            <br>
            Official site:{{$model['official_site']}};
            <br>
            Instagram:{{$model['instagram']}};
            <br>
            Twitter:{{$model['twitter']}};
            <br>
            Fan Centro:{{$model['fan_centro']}};
            <br>
            Visual video:{{$model['n_video_visual']}};
            <br>
            Subscribers:{{$model['subscribers']}};
            <br>
            Joined:{{$model['joined']}};
            <br>
            Rank:{{$model['monthly_ranking']}};
            <br>
            <hr>

        @elseif($model['available'] == 'not')
            <img src="{{$model['link_img']}}">
            <h3>{{$model['modelName']}}</h3>
            <small>Not Available</small>
            <hr>
        @endif

    @endforeach


@endsection
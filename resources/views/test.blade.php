@extends('layouts.simple')

@section('content')
    <hr>
    <h1>Pornstars</h1>
    <hr>
@foreach($all_pornstars as $pornstar)

    <img src="{{$pornstar['link_img']}}">
    <h3>{{$pornstar['full_name']}}</h3>
    <hr>
@endforeach
    <hr>
    <h1>Amateur models</h1>
    <hr>
@foreach($all_amateur as $pornstar)

    <img src="{{$pornstar['link_img']}}">
    <h3>{{$pornstar['full_name']}}</h3>
    <hr>
@endforeach

@endsection



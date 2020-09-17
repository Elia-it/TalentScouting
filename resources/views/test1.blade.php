@extends('layouts.simple')

@section('content')
    <form method="post" action="/test1">
        @csrf
        <select class="form-control" id="test_input" name="test_input">
            <option value="" >Pornstars/models</option>
            <option value="pornstar">Pornstars</option>
            <option value="model">Models</option>
        </select>
        <input type="submit">
    </form>

    @foreach($all_pornstars as $pornstar)
        <h3>{{$pornstar->full_name}}</h3>
        <img src="{{$pornstar->link_img}}">
        <hr>
        <br>
    @endforeach

@endsection

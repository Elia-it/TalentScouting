@extends('layouts.simple')

@section('content')
    <hr>
    <h1>WORKS</h1>
    <hr>

    <form action="{{url('http://127.0.0.1:8000/api/upload_csv')}}" method="post" enctype="multipart/form-data">
        @csrf
        <fieldset>
            <input type="file" name="file" />
            <input type="submit" name="upload" value="Upload" />
        </fieldset>
    </form>
@endsection


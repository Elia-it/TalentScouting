@extends('layouts.simple')

@section('content')
    <hr>
    <h1>UPLOAD CSV</h1>
    <hr>

    <form action="{{route('upload_csv')}}" method="post" enctype="multipart/form-data">
        @csrf
        <fieldset>
            <input type="file" name="file" />
            <input type="submit" name="upload" value="Upload" />
        </fieldset>
    </form>
@endsection


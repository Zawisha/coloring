@extends('layouts.admin')
@section('content')
    @isset($Data_One)
        {{ $Data_One }}
    @endisset
    <div class="container justify-content-center text-center">Раскраска добавлена успешно</div>

@endsection

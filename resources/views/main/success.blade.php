@extends('layouts.main')
@section('content')
    <div class="container">
        <div class="col-12 success_marg">
            Спасибо! Раскраска успешно загружена и отправлена на модерацию.
        </div>
        <button type="button" class="btn btn-info" onclick="window.location='{{ url("/add_coloring_user") }}'">Загрузить еще раскраску</button>
    </div>
@endsection

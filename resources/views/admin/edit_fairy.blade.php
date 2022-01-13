@extends('layouts.admin')
@section('content')
    
    <div class="container justify-content-center text-center">Редактирование сказки</div>
    <edit-fairy-title-component :current_fairy_vue="{{ Request::segment(3) }}"></edit-fairy-title-component>
@endsection

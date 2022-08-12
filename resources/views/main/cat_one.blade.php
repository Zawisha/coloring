@extends('layouts.main')
@section('content')
    <h1 class="new_cat">Выбрана категория:{{ $cat_name }}</h1>
<front-cat-one-with-colorings-component :auth_user='@json($auth_user)'></front-cat-one-with-colorings-component>
@endsection

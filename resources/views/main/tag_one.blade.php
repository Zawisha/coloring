@extends('layouts.main')
@section('content')
    <h1 class="new_cat">Бесплатные раскраски:{{ $tag_name }}</h1>
<front-tag-one-with-colorings-component :auth_user='@json($auth_user)'></front-tag-one-with-colorings-component>
@endsection

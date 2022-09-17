@extends('layouts.main')
@section('content')
    <streak-component></streak-component>
<front-coloring-list-component :auth_user='@json($auth_user)'></front-coloring-list-component>
@endsection

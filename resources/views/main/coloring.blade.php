@extends('layouts.main')
@section('content')
    <streak-component></streak-component>
<front-coloring-list-component :auth_user='@json($auth_user)' :search_q='@json($search_q)'></front-coloring-list-component>
@endsection

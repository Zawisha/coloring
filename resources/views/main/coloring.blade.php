@extends('layouts.main')
@section('content')
<front-coloring-list-component :auth_user='@json($auth_user)'></front-coloring-list-component>
@endsection

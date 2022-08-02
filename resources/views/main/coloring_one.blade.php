@extends('layouts.main')
@section('content')
<front-coloring-one-component :auth_user='@json($auth_user)'></front-coloring-one-component>

@endsection

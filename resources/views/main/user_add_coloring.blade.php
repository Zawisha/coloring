@extends('layouts.main')
@section('content')
<user-add-coloring-component :auth_user='@json($auth_user)'></user-add-coloring-component>
@endsection

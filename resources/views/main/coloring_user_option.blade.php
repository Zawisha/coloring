@extends('layouts.main')
@section('content')

<front-coloring-user-option :auth_user='@json($auth_user)' :slug='@json($slug)'></front-coloring-user-option>
@endsection

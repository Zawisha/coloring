@extends('layouts.main')
@section('content')
<front-coloring-one-component :auth_user='@json($auth_user)' :same_colorings='@json($same_colorings)'></front-coloring-one-component>

@endsection

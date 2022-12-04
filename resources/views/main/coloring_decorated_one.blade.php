@extends('layouts.main')
@section('content')

<front-coloring-decorated-one-component :auth_user='@json($auth_user)' :same_colorings='@json($same_colorings)' :slugok='@json($slugMain)' :coloring_decorated='@json($coloring_decorated)'></front-coloring-decorated-one-component>

@endsection

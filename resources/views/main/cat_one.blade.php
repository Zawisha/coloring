@extends('layouts.main')
@section('content')
<front-cat-one-with-colorings-component :auth_user='@json($auth_user)'></front-cat-one-with-colorings-component>
@endsection

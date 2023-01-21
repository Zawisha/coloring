@extends('layouts.main')
@section('content')

<front-decorated-user-list-component :auth_user='@json($auth_user)' ></front-decorated-user-list-component>
@endsection


@extends('layouts.main')
@section('content')
<profile-component :auth_user='@json($auth_user)'></profile-component>
@endsection

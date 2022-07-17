@extends('layouts.main')
@section('content')
<front-liked-component :auth_user='@json($auth_user)'></front-liked-component>
@endsection

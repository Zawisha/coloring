@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="/password/email">
                {!! csrf_field() !!}

                @if (count($errors) > 0)
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                Email:
                <input type="email" name="email" value="{{ old('email') }}">
                <br>

                <button type="submit">
                 Восстановить пароль
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

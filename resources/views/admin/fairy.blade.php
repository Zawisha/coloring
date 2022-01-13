@extends('layouts.admin')
@section('content')
    <fairy-pagination-component :current_page_vue="{{ Request::segment(4) }}" :current_fairy_vue="{{ Request::segment(3) }}"></fairy-pagination-component>
    <div class="container justify-content-center text-center">Работа с ТЕКУЩЕЙ страницей</div>
    <div class="container justify-content-center text-center">Текущая страница {{ Request::segment(4) }}</div>
    <div class="container edit_text_black">
        <div class="row justify-content-center">
            @if (count($errors) > 0)
                <div class = "alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-md-8">
                <form action="{{ route('store_from_editor') }}" method="POST">
                        @csrf
                    <textarea id="editor1" name="description">{{ $description_from_db ?? ""}}</textarea>
                    <input type="hidden" value="{{ Request::segment(3) }}" name="current_fairy">
                    <input type="hidden" value="{{ Request::segment(4) }}" name="current_page">
                    <button type="submit" class="btn btn-light add_coloring_title" onsubmit="return false;">Сохранить текущую страницу</button>
                </form>
            </div>
        </div>
    </div>
@endsection




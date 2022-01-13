@extends('layouts.main')
@section('content')
<div class="single-product-area section-padding-100 clearfix">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mt-50">
                    @foreach($coloring[0]->categories  as $cat)
                        <li class="breadcrumb-item"><a href="/">#{{ $cat->name }}</a></li>
                        @endforeach
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-7">
                <img src="{{url('/images/colorings/'.$coloring[0]->img )}}"  alt="Image"/>
            </div>
            <div class="col-12 col-lg-5">
                <div class="single_product_desc">
                    <!-- Product Meta Data -->
                    <div class="product-meta-data">
                        <div class="line"></div>
                            <h6>{{ $coloring[0]->name }}</h6>
                    </div>

                    <div class="short_overview my-5">
                    <p>{{ $coloring[0]->description }}</p>
                    </div>
                    <a href="{{url('download/'.$coloring[0]->img )}}" >
                        <button class="btn amado-btn download_button_col">Скачать</button>
                    </a>
{{--                    <a href="{{url('/print')}}" >--}}
{{--                        <button type="submit" name="addtocart" value="5" class="btn amado-btn download_button_col">Распечатать</button>--}}
{{--                    </a>--}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Coloring</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('/css/app1.css') }}"  rel="stylesheet">
    <link href="{{ asset('css/css/core-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/css/owl.carousel.css') }}" rel="stylesheet" />
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/js_main/jquery/jquery-2.2.4.min.js') }}" ></script>
    <!-- Popper js -->
    <script href="{{ asset('js/js_main/popper.min.js') }}" ></script>
    <!-- Bootstrap js -->
    <script href="{{ asset('js/js_main/bootstrap.min.js') }}" ></script>
    <!-- Plugins js -->
    <script href="{{ asset('js/js_main/plugins.js') }}" ></script>
    <!-- Active js -->
    <script src="{{ asset('js/js_main/active.js') }}" ></script>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(88651975, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/88651975" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-1LL2L8VK8T"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-1LL2L8VK8T');
    </script>
</head>
<body class="antialiased" >
<div id="app">
<div>
    <!-- Search Wrapper Area Start -->
    <div class="search-wrapper section-padding-100">
        <div class="search-close">
            <i class="fa fa-close" aria-hidden="true"></i>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="search-content">
                        <form action="#" method="get">
                            <input type="search" name="search" id="search" placeholder="Type your keyword...">
{{--                            <button type="submit"><img src="img/core-img/search.png" alt=""></button>--}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Search Wrapper Area End -->
    <!-- ##### Main Content Wrapper Start ##### -->
    <div class="main-content-wrapper d-flex clearfix">

        <!-- Mobile Nav (max width 767px)-->
        <div class="mobile-nav">
            <!-- Navbar Brand -->
            <div class="amado-navbar-brand">
                <a href="/">  <img src="{{ url('/images/main_images/logo.jpg') }}" alt="logo icon"></a>
{{--                <a href="index.html"><img src="img/core-img/logo.png" alt=""></a>--}}
            </div>
            <!-- БУРГЕР МЕНЮ -->
            <div class="amado-navbar-toggler">
                <span></span><span></span><span></span>
            </div>
        </div>

        <!-- Header Area Start -->
{{--        КЛАСС ДЛЯ ИЗМЕНЕНИЯ LEFT --}}
        <header class="header-area clearfix">
            <!-- Close Icon -->
            <div class="nav-close">
                <i class="fa fa-close" aria-hidden="true"></i>
            </div>
            <!-- Logo -->
            <div class="logo">
                <a href="/"><img src="{{ url('/images/main_images/logo.jpg') }}" alt="logo icon"></a>
            </div>
            <!-- Amado Nav -->
            <nav class="amado-nav">
                <ul>
                    <li class="{{ Request::path() ==  '/' ? 'active' : ''  }}"><a href={{ route('home') }} >Раскраски</a></li>
{{--                    <li class="active"><a href="shop.html">Shop</a></li>--}}
                    <li class="{{ Request::path() ==  'fairy-list' ? 'active' : ''  }}"><a href={{ route('front_fairy_list') }}>Сказки</a></li>
                    <li class="{{ Request::path() ==  'video-list' ? 'active' : ''  }}"><a href={{ route('front_video_list') }}>Видео</a></li>
                </ul>
            </nav>
            <!-- Button Group -->
            <div class="amado-btn-group mt-30 mb-100">
                @auth
                @if (\App\Models\Admins::isAdmin())
                <a href="/admin" class="btn amado-btn mb-15">Админка</a>
                @endif
                @endauth
                    @guest
                        <a href="/login" class="btn amado-btn mb-15">Войти</a>
                        <a href="/register" class="btn amado-btn mb-15">Регистрация</a>
                    @endguest
            </div>
            <!-- Cart Menu -->
            <div class="cart-fav-search mb-100">
{{--                <a href="cart.html" class="cart-nav"><img src="img/core-img/cart.png" alt=""> Cart <span>(0)</span></a>--}}
{{--                <a href="#" class="fav-nav"><img src="img/core-img/favorites.png" alt=""> Favourite</a>--}}
{{--                <a href="#" class="search-nav"><img src="img/core-img/search.png" alt=""> Search</a>--}}
            </div>
            <!-- Social Button -->
            <div class="front-visible-menu">
            <div class="social-info d-flex justify-content-between ">
                <front-categories-component></front-categories-component>
{{--                <a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a>--}}
{{--                <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>--}}
{{--                <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>--}}
{{--                <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>--}}
            </div>
            </div>
        </header>
        <!-- Header Area End -->

        @yield('content')
    </div>
    <!-- ##### Main Content Wrapper End ##### -->

    <!-- ##### Newsletter Area Start ##### -->
    <section class="newsletter-area section-padding-100-0">
        <div class="container">
            <div class="row align-items-center">
                <!-- Newsletter Text -->
                <div class="col-12 col-lg-6 col-xl-7">
                    <div class="newsletter-text mb-100">
                        <h2>Сайт для детей и их родителей</h2>
                        <p>На нашем сайте вы можете найти раскарски, сказки, видео и другую полезную информацию для тедетей и их родителей. Приятного времяпрепровождения.</p>
                        <p>{{ now()->year }} г. «Раскраски». Все права защищены. </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


</div>
</div>
</body>
</html>


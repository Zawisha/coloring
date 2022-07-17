<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Coloring</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('/css/app1.css') }}"  rel="stylesheet">
    <link href="{{ asset('/css/app2.css') }}"  rel="stylesheet">
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
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
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

    <div class="container-fluid">
        <div class="row new_head">
        <div class="col-3 new_head_icon_top">
            <a href="/" ><img src="{{ url('/images/main_images/logo.jpg') }}" alt="logo icon" class="new_head_logo"></a>
        </div>
            <div class="col-6 col-lg-4 new_head_search_top">
                <input id="general_search" class="new_head_search" placeholder="поиск раскраски сказки или видео">
            </div>
            <div class="new_head_search_icon d-none d-lg-block  col-lg-1 text-left"></div>
            <div class="new_head_help_circle_icon  d-none d-lg-block  col-lg-2"></div>
            <a class="col-3 col-lg-2 row justify-content-center" href="/profile">
{{--                <img src="{{ url('/images/colorings/1_1643563682.jpg') }}" class="new_head_user_icon ">--}}
                @auth
                <ava-component :auth_user='@json($auth_user)'></ava-component>
                @endauth
                @guest
                    <span class="login_button d-flex justify-content-center align-items-center">
                Войти
                     </span>
                @endguest
            </a>
        </div>
    </div>

    <div class="container-fluid container_header_align">
        <div class="row">
        <div class="col-2 left_menu_block hide_on_mob">
            <a href="/" >  <div class="left_menu_nav row col-12">
                <span id="myH1" class="iconify left_menu_icon_f iconify_main" style="color: #6968a9;"   data-icon="ion:color-palette-outline"></span>
                <span class="left_menu_nav_text"><span class="iconify "  data-icon="bi:dot" style="color: #6968a9;" data-width="20" data-height="20"></span> РАСКРАСКИ</span>
            </div>
            </a>
            <a href="/" >  <div class="left_menu_nav row col-12">
                <span class="iconify iconify_main" style="color: #6968a9;" data-icon="icon-park-outline:book-open"></span>
                <span class="left_menu_nav_text"><span class="iconify " data-icon="bi:dot" style="color: #6968a9;" data-width="20" data-height="20"></span> СКАЗКИ</span>
            </div>
            </a>
            <a href="/" >
            <div class="left_menu_nav row col-12">
                <span class="iconify iconify_main" style="color: #6968a9;" data-icon="ion:videocam-outline"></span>
                <span class="left_menu_nav_text"><span class="iconify " data-icon="bi:dot" style="color: #6968a9;" data-width="20" data-height="20"></span> ВИДЕО</span>
            </div>
            </a>
            <div class="left_menu_nav_empty row col-12">
            </div>
            <div class="left_menu_nav_reklama row col-12">
                <div>
{{--                    <img src="{{ url('/images/colorings/1_1651100734.jpg') }}" class="menu_img_rek">--}}
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-8 green_menu_header  ">
            <div class="col-12 row green_menu_header  ">
{{--                align-self-center--}}
               <a href="/cat"  class="green_menu_element col green_menu_element_0 d-flex justify-content-center align-items-center">Категории</a>
               <a href="/"  class="green_menu_element col green_menu_element_1 d-flex justify-content-center align-items-center">Топ <br>раскрасок</a>
               <a href="/"  class="green_menu_element col green_menu_element_2 d-flex justify-content-center align-items-center">Топ авторов</a>
               <a href="/"  class="green_menu_element col green_menu_element_3 d-flex justify-content-center align-items-center">Топ художников</a>
               <a href="/"  class="green_menu_element col green_menu_element_4 d-flex justify-content-center align-items-center">Топ новых раскрасок</a>
            </div>
                        @yield('content')
        </div>
        <div class="col-2 right_menu_block hide_on_mob">
            <div class="right_menu_nav_reklama row col-12">
                <div>
{{--                    <img src="{{ url('/images/colorings/1_1651087261.jpg') }}" class=" ">--}}
                </div>
            </div>
        </div>
        </div>
    </div>


{{--    <!-- Search Wrapper Area Start -->--}}
{{--    <div class="search-wrapper section-padding-100">--}}
{{--        <div class="search-close">--}}
{{--            <i class="fa fa-close" aria-hidden="true"></i>--}}
{{--        </div>--}}
{{--        <div class="container">--}}
{{--            <div class="row">--}}
{{--                <div class="col-12">--}}
{{--                    <div class="search-content">--}}
{{--                        <form action="#" method="get">--}}
{{--                            <input type="search" name="search" id="search" placeholder="Type your keyword...">--}}
{{--                            <button type="submit"><img src="img/core-img/search.png" alt=""></button>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <!-- Search Wrapper Area End -->--}}
{{--    <!-- ##### Main Content Wrapper Start ##### -->--}}
{{--    <div class="main-content-wrapper d-flex clearfix">--}}
{{--        <!-- Mobile Nav (max width 767px)-->--}}
{{--        <div class="mobile-nav">--}}
{{--            <!-- Navbar Brand -->--}}
{{--            <div class="amado-navbar-brand">--}}
{{--                <a href="/">  <img src="{{ url('/images/main_images/logo.jpg') }}" alt="logo icon"></a>--}}
{{--                <a href="index.html"><img src="img/core-img/logo.png" alt=""></a>--}}
{{--            </div>--}}
{{--            <!-- БУРГЕР МЕНЮ -->--}}
{{--            <div class="amado-navbar-toggler">--}}
{{--                <span></span><span></span><span></span>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        --}}
{{--        <header class="header-area clearfix">--}}
{{--            <!-- Close Icon -->--}}
{{--            <div class="nav-close">--}}
{{--                <i class="fa fa-close" aria-hidden="true"></i>--}}
{{--            </div>--}}
{{--            <!-- Logo -->--}}
{{--            <div class="logo">--}}
{{--                <a href="/"><img src="{{ url('/images/main_images/logo.jpg') }}" alt="logo icon"></a>--}}
{{--            </div>--}}
{{--            <!-- Amado Nav -->--}}
{{--            <nav class="amado-nav">--}}
{{--                <ul>--}}
{{--                    <li class="{{ Request::path() ==  '/' ? 'active' : ''  }}"><a href={{ route('home') }} >Раскраски</a></li>--}}
{{--                    <li class="active"><a href="shop.html">Shop</a></li>--}}
{{--                    <li class="{{ Request::path() ==  'fairy-list' ? 'active' : ''  }}"><a href={{ route('front_fairy_list') }}>Сказки</a></li>--}}
{{--                    <li class="{{ Request::path() ==  'video-list' ? 'active' : ''  }}"><a href={{ route('front_video_list') }}>Видео</a></li>--}}
{{--                </ul>--}}
{{--            </nav>--}}
{{--            <!-- Button Group -->--}}
{{--            <div class="amado-btn-group mt-30 mb-100">--}}
{{--                @auth--}}
{{--                @if (\App\Models\Admins::isAdmin())--}}
{{--                <a href="/admin" class="btn amado-btn mb-15">Админка</a>--}}
{{--                @endif--}}
{{--                @endauth--}}
{{--                    @guest--}}
{{--                        <a href="/login" class="btn amado-btn mb-15">Войти</a>--}}
{{--                        <a href="/register" class="btn amado-btn mb-15">Регистрация</a>--}}
{{--                    @endguest--}}
{{--            </div>--}}

{{--            <div class="cart-fav-search mb-100">--}}

{{--            </div>--}}

{{--            <div class="front-visible-menu">--}}
{{--            <div class="social-info d-flex justify-content-between ">--}}
{{--                <front-categories-component></front-categories-component>--}}

{{--            </div>--}}
{{--            </div>--}}
{{--        </header>--}}


{{--        @yield('content')--}}
{{--    </div>--}}
{{--    <!-- ##### Main Content Wrapper End ##### -->--}}

{{--    <!-- ##### Newsletter Area Start ##### -->--}}
{{--    <section class="newsletter-area section-padding-100-0">--}}
{{--        <div class="container">--}}
{{--            <div class="row align-items-center">--}}
{{--                <!-- Newsletter Text -->--}}
{{--                <div class="col-12 col-lg-6 col-xl-7">--}}
{{--                    <div class="newsletter-text mb-100">--}}
{{--                        <h2>Сайт для детей и их родителей</h2>--}}
{{--                        <p>На нашем сайте вы можете найти раскарски, сказки, видео и другую полезную информацию для тедетей и их родителей. Приятного времяпрепровождения.</p>--}}
{{--                        <p>{{ now()->year }} г. «Раскраски». Все права защищены. </p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}


</div>
</div>
</body>
</html>
<script>
    // alert(window.innerWidth);
    if(window.innerWidth >992 && window.innerWidth<1172)
    {
        for(let i = 0; i < 3; i++)
        {
            document.getElementsByClassName("iconify_main")[i].setAttribute("data-width", "0");
            document.getElementsByClassName("iconify_main")[i].setAttribute("data-height", "0");
        }

    }
    if(window.innerWidth >1172 && window.innerWidth<1232)
    {
        for(let i = 0; i < 3; i++)
        {
            document.getElementsByClassName("iconify_main")[i].setAttribute("data-width", "30");
            document.getElementsByClassName("iconify_main")[i].setAttribute("data-height", "30");
        }

    }
    if(window.innerWidth>1231)
    {
        for(let i = 0; i < 3; i++)
        {
            document.getElementsByClassName("iconify_main")[i].setAttribute("data-width", "40");
            document.getElementsByClassName("iconify_main")[i].setAttribute("data-height", "40");
        }
    }
    if(window.innerWidth<577)
    {
            document.getElementById("general_search").setAttribute("placeholder", "ПОИСК");
    }
    if(window.innerWidth<993)
    {
        $(".hide_on_mob").hide();
    }
</script>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    @include('head.headContent')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <script type="text/javascript" src={{ asset('js/home.js') }} defer></script>
  <body>        
    <div class="video-container">
      <video autoplay poster="" class="video-parallax video-mobile" loop muted>
        <source src="img/homepage.mp4" type="video/mp4">
      </video>
      <img class="video-parallax image-desktop" src="/img/homepage.jpg" alt=""/>
      <div id="logo-container" class="logo-container">
        <img class="logo" src="/img/refurniture.svg" alt="" width="300" />
        <h1 class="title">We make new<br>beginnings <span id="keyword" class="keyword">easier.<span><span id="cursor"></span></h1>
        <div class="link-container">
            <a class="bi bi-arrow-right-circle-fill link-to-join" href={{url('/join')}}></a>
        </div>
      </div>
    </div>
    
    <div class="content"></div>

    
  </body>
</html>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    @include('head.headContent')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <script type="text/javascript" src={{ asset('js/home.js') }} defer></script>
  <body>        
    <div class="video-container">
      <!--<video autoplay poster="" class="video-parallax" loop muted>
        <source src={{asset('img/homepage.mp4')}} type="video/mp4">
      </video>-->
      <img class="video-parallax" src="/img/homepage.jpg" alt=""/>
      <div class="logo-container">
        <img class="logo" src="/img/refurniture.svg" alt="" width="300" />
        <h1 class="title">We make new<br>begginings <span id="keyword" class="keyword">easier.<span><span id="cursor"></span></h1>
      </div>
    </div>
    
    <div class="fullScreenPhoto2"></div>

    
  </body>
</html>

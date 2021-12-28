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
    
    <div class="content-container">
        <div class="holder"> </div>
        <div class="main-content">
            <div>
                <h1 class="content-title">For a more<br>sustainable future.</h1>
            </div>
            <div class="info">
                <div class="navigation">
                    <h1>Navigation</h1>
                    <a href={{url('/')}}><h4>Home</h4></a>
                    <a href={{url('/')}}><h4>Sell to Us</h4></a>
                    <a href={{url('/')}}><h4>About Us</h4></a>
                    <a href={{url('/')}}><h4>Contact Us</h4></a>
                </div>
                <div class="contacts">
                    <h1>Contacts</h1>
                    <h4>support@refurniture.pt</h4>
                    <h4>+351 912345678</h4>
                </div>
            </div>
            <div class="socials">
                <a class="bi bi-facebook" href="https://www.facebook.com/pages/refurniture/" target="__blank"></a>
                <a class="bi bi-linkedin" href="https://www.linkedin.com/company/refurniture/" target="__blank"></a>
                <a class="bi bi-twitter" href="https://www.twitter.com/refurniture/" target="__blank"></a>
            </div>
        </div>
    </div>
    <footer class="footer-container">
        <h6 class="footer-text">© 2021 reFurniture. All Rights Reserved. </h6>
    </footer>
 
  </body>
</html>

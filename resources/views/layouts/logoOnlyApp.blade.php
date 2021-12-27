<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  @include('head.headContent')
  <body>
    <main>
      <header>
        @include('layouts.logoOnlyHeader')
      </header>
      <section id="content">
        @yield('content')
      </section>
    </main>
  </body>
</html>

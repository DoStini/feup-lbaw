<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  @include('head.headContent')
  <body>
    @include('partials.errormodal')
    <main>
      <header>
        @include('layouts.header')
      </header>
      <section id="content">
        @yield('content')
      </section>
    </main>
</body>

</html>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  @include('head.headContent')
  <body>
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
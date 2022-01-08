<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  @include('head.headContent')
  <body>
    <main>
      <header>
        @include('layouts.header')
      </header>
      <section id="content h-100">
        @yield('content')
      </section>
    </main>
</body>

</html>
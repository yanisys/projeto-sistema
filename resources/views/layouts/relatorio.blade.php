<html>
<head>
    <title>{{ (isset($titulo) ? $titulo : 'Sem Título') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    @yield('css')
</head>
<body>

  <header>
      @yield('header')
  </header>

  <footer>
      @yield('footer')
  </footer>

  <main>
      @yield('conteudo')
  </main>

</body>
</html>
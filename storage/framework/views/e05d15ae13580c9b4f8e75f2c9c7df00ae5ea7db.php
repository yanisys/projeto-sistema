<html>
<head>
    <title><?php echo e((isset($titulo) ? $titulo : 'Sem Título')); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo $__env->yieldContent('css'); ?>
</head>
<body>

  <header>
      <?php echo $__env->yieldContent('header'); ?>
  </header>

  <footer>
      <?php echo $__env->yieldContent('footer'); ?>
  </footer>

  <main>
      <?php echo $__env->yieldContent('conteudo'); ?>
  </main>

</body>
</html>
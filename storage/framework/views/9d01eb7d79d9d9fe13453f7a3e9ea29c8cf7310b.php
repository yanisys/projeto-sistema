<html>
<head>
    <title><?php echo e($titulo); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="<?php echo e(asset('public/css/relatorio.css')); ?>">
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
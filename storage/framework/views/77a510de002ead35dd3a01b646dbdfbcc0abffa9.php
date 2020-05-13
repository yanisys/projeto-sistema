<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="<?php echo e(asset('public/images/favicon.ico')); ?>">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Nicola')); ?></title>

    <!-- Scripts -->
    <script src="<?php echo e(asset('public/js/jquery3.1.1.min.js')); ?>"></script>
 <!--   <script src="<?php echo e(asset('public/js/jquery-ui.js')); ?>"></script> -->
    <script src="<?php echo e(asset('public/js/sweetalert2.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/js/bootstrap3.3.7.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/js/jquerymask1.14.10.js')); ?>"></script>

    <!-- <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script> -->

    <!-- Fonts -->
    <!-- <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css"> -->

    <!-- Styles -->
    <!-- <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="<?php echo e(asset('public/css/bootstrap3.3.7.css')); ?>" >
   <!-- <link rel="stylesheet" href="<?php echo e(asset('public/css/jquery-ui.css')); ?>" > -->
    <link rel="stylesheet" href="<?php echo e(asset('public/css/sweetalert2.css')); ?>">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/97d533847a.js" crossorigin="anonymous"></script>
  <!--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="<?php echo e(asset('public/css/style_v1.10.css')); ?>">
<!--    <link rel="stylesheet" href="<?php echo e(asset('public/css/all.css')); ?>"> -->
</head>
<body>
    <div id="loadOverlay" style="background-color:#fff; position:absolute; top:0px; left:0px; width:100%; height:100%; z-index:2000;"></div>
    <div id="app">
        <main>
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
    <!-- <script src="<?php echo e(js_versionado('app.js')); ?>" defer></script> -->
    <?php echo $__env->yieldContent('painel-modal'); ?>
    <?php echo $__env->yieldContent('custom_js'); ?>
    <?php echo $__env->yieldContent('js_variaveis'); ?>

    <style> #loadOverlay{display: none;} </style>
</body>
</html>

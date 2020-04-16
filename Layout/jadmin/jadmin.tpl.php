<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $this->nombreApp ?></title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
    <script src="https://kit.fontawesome.com/e2761a2095.js" crossorigin="anonymous"></script>
    <?= $this->imprimirLibrerias('head', 'principal') ?>
    <?php $this->incluir('jida-js') ?>
</head>
<body class="text-left">
<div class="app-admin-wrap layout-sidebar-large clearfix">
    <?= $this->incluir('elementos/header') ?>
    <?= $this->menu ?>
    <div class="main-content-wrap sidenav-open d-flex flex-column">
        <?= $this->contenido() ?>
        <?= $this->incluir('elementos/footer') ?>
    </div>
</div>

<?= $this->imprimirLibrerias('js', 'principal') ?>

</body>
</html>
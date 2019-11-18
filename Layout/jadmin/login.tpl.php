<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $this->nombreApp ?></title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
    <?= $this->imprimirLibrerias('head', 'login') ?>
</head>

<body class="text-left">
    <div class="auth-layout-wrap" style="background-image: url(<?= $this->urlTema ?>/htdocs/images/photo-wide-4.jpg)">
        <div class="auth-content">
            <?= $this->contenido() ?>
        </div>
    </div>

    <?= $this->imprimirLibrerias('js', 'login') ?>
</body>
</html>
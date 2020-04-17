<div class="card">
    <h4 class="card-header"><?= $this->texto('title') ?></h4>
    <div class="card-body">
        <?= \Jida\Medios\Mensajes::imprimirMsjSesion() ?>
        <?= $this->formulario ?>
        <div class="clear"></div>
    </div>
</div>
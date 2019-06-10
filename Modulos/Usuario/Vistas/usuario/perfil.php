<div class="card">
    <h4 class="card-header">Usuario: <?= $this->name?></h4>
    <div class="card-body">
        <div id="vista" class="row">
            <div class="col-12">
                <?= \Jida\Medios\Mensajes::imprimirMsjSesion() ?>
            </div>
            <div class="col-12">
                <form action="<?= \Jida\Manager\Estructura::$urlBase ?>/jadmin/usuario/perfil/<?= $this->id_usuario?>"
                        method="post">
                    <?php foreach ($this->perfiles as $perfil): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input"
                               type="checkbox"
                               name="id_perfil[]"
                               value="<?= $perfil['id_perfil'] ?>"
                               id="defaultCheck<?= $perfil['id_perfil'] ?>"
                                <?= array_intersect([$perfil['id_perfil']], $this->listaPerfiles) ? 'checked' : null ?>
                                >
                        <label class="form-check-label" for="defaultCheck<?= $perfil['id_perfil'] ?>">
                            <?= $perfil['perfil'] ?>
                        </label>
                    </div>
                    <?php endforeach; ?>
                    <div class="form-group text-right">
                        <button type="submit" name="btnGestionPerfiles" class="btn btn-primary" value="true">Guardar</button>
                        <a href="<?= \Jida\Manager\Estructura::$urlBase ?>/jadmin/usuario/" class="btn btn-primary">Volver a Usuarios</a>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

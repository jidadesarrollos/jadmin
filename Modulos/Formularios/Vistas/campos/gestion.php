<div id="jidaGestionCampos" data-formulario="<?= $this->idFormulario ?>">
    <header class="page-header">
        <h1>Configuracion de Formularios...</h1>
    </header>
    <div class="alert alert-info">
        <h3>Ten en cuenta:</h3>
        <ol>
            <li>
                <strong>Si deseas editar el orden de los campos</strong> Haz click en el bot&oacute;n <strong>Editar
                    Orden</strong> que se
                encuentra al final de la lista de campos y luego haciendo click en cada campo colocalo en la posicion
                que deseas.
            </li>
            <li>
                <strong>Si deseas editar la conf. del campo</strong> Debes hacer <i>doble click</i> sobre el elemento
            </li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-3 col-xs-12 col-sm-3">
            <div>
                <button
                        id="btnEditOrden"
                        title="editar orden"
                        class="btn btn-primary"
                        value="1">
                    <span class="fa fa-edit fa-lg"/>
                    Editar orden
                </button>
            </div>
            <div
                    id="listaCamposFormulario"
                    class="list-group list-form-item mt-15"
                    data-url="<?=$this->url?>">
                <?php
                foreach ($this->campos as $key => $campo):
                    if (is_object($campo)) :
                        ?>

                        <a href="#formCampos"
                           id="campoForm-<?= $campo->id ?>"
                           data-campo="<?= $campo->id ?>"
                           data-modulo="<?= $this->moduloFormulario ?>"
                           class="list-group-item"> <?= $campo->name ?></a>

                        <?php
                    endif;
                endforeach
                ?>
            </div>
        </div>
        <div class="col-md-9 col-sm-9">
            <section class="form-campos">

            </section>
        </div>
    </div>
</div>
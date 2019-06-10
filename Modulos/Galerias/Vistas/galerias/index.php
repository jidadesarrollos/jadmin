<section class="card">
    <div class="card-header">
        <h4 class="card-title">Gestión de Imagenes</h4>
    </div>
    <div class="card-body">
        <button type="button"
                class="btn btn-primary"
                data-url-envio=<?= $this->urlEnvio ?>
                id="btnCargaImagen">
            <span class="fa fa-camera-retro"></span> Subir Imagen
        </button>

        <div class="clearfix"></div>

        <div class="album">
            <div id="lightgallery" class="row jida-galeria-media">

                <?php foreach ($this->media as $key => $item) : ?>

                    <section class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                        <figure class="card mt-3 shadow-sm">

                            <div class='overlay item' data-id="<?= $item['id_objeto_media'] ?>"
                                 data-src="<?= $item['url']['original']; ?>">
                                <img src="<?= $item['url']['400x400'] ?>" class="img-fluid"/>
                            </div>

                            <div class="menu-gallery-jadmin">
                                <div class="btn-group">
                                    <a data-accion="editar" class="btn btn-sm btn-primary">
                                <span class="fa fa-edit"
                                      data-toggle="tooltip"
                                      data-placement="top"
                                      title="Editar"></span>
                                    </a>
                                    <a data-accion="eliminar" class="btn btn-sm btn-primary">
                                <span class="fa fa-trash"
                                      data-toggle="tooltip"
                                      data-placement="top"
                                      title="Eliminar"></span>
                                    </a>
                                </div>
                            </div>
                        </figure>
                    </section>

                <?php endforeach; ?>

                <div class="msj-no-img col-md-12 mt-3">
                    <div class="alert alert-warning">
                        No se encontraron imágenes en este proyecto.
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<script type="mostache-script" id="imgTemplate">

	<section class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <figure class="card mt-3 shadow-sm jcargafile">
            <div class='overlay item' data-src="{{img}}" data-id="{{id}}">
                <img src="{{src}}" alt="{{alt}}" class="img-fluid">
            </div>
                <div class="menu-gallery-jadmin">
                    <div class="btn-group">
                        <a data-accion="editar"  class="btn btn-sm btn-primary disabled">
                        <span class="fa fa-edit"
                              data-toggle="tooltip"
                              data-placement="top"
                              title="Editar"></span>
                        </a>
                        <a data-accion="eliminar" class="btn btn-sm btn-primary disabled">
                        <span class="fa fa-trash"
                              data-toggle="tooltip"
                              data-placement="top"
                              title="Eliminar"></span>
                        </a>
                    </div>
                </div>
        </figure>
    </section>




</script>

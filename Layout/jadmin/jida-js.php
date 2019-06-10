<?php

#\Jida\Medios\Debug::imprimir([$this], true);
?>

<script>
    (function () {

        window.jida = {
            'url': {
                'base': "<?= $this->urlBase?>",
                'modulo': '<?= $this->urlModulo?>',
                'actual': '<?=$this->url?>'
            }

        }
    })();
</script>

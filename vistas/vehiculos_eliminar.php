<!-- nuevo cliente -->
<div class="btn_nuevo">
    <a href="<?php echo URL_BASE . 'clientes.php' ?>" class="w3-button w3-blue"> Atras</a>
</div>

<hr>

<div class="w3-modal-content">

    <?php if (isset($respuesta)) : ?>
        <div class="w3-panel w3-pale-green w3-border">
            <p><?php echo $respuesta ?></p>
        </div>
    <?php endif; ?>

</div>
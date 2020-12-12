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

    <header class="w3-container w3-light-grey">
        <h2>Editar Cliente</h2>
    </header>
    <div class="w3-container">

        <?php foreach ($errores as $error) : ?>
            <span class="w3-text-red"><?php echo $error ?> </span><br>
        <?php endforeach; ?>


        <form action="<?php echo URL_BASE . 'clientes_editar.php?id='.$id ?>" method="POST">

            <p>
                <input name="id" value="<?php echo $id ?>" type="hidden">
            </p>

            <p>
                <input name="numero_documento" class="w3-input w3-border" value="<?php echo $numero_documento ?>" maxlength="50" type="text" placeholder="Número Documento">
            </p>

            <p>
                <input name="nombre" class="w3-input w3-border" value="<?php echo $nombre ?>" type="text" maxlength="50" placeholder="Nombre">
            </p>

            <p>
                <input name="apellidos" class="w3-input w3-border" value="<?php echo $apellidos ?>" type="text" maxlength="50" placeholder="Apellidos">
            </p>

            <p>
                <button class="w3-button w3-blue w3-padding-large" type="submit">Guardar</button>
            </p>
        </form>


    </div>



</div>
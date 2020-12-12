<!-- nuevo cliente -->
<div class="btn_nuevo">
    <a href="<?php echo URL_BASE . 'usuarios.php' ?>" class="w3-button w3-blue"> Atras</a>
</div>

<hr>

<div class="w3-modal-content">

    <!-- respuestas -->
    <div class="w3-container">
        <?php if (isset($respuesta)) : ?>
            <div class="w3-panel w3-pale-green w3-border">
                <p><?php echo $respuesta ?></p>
            </div>
        <?php endif; ?>
    </div>

    <header class="w3-container w3-light-grey">
        <h2>Nuevo password</h2>
    </header>

    <div class="w3-container">

        <!-- errores de validacion  -->
        <?php foreach ($errores as $error) : ?>
            <span class="w3-text-red"><?php echo $error ?> </span><br>
        <?php endforeach; ?>


        <form action="<?php echo URL_BASE . 'usuarios_password.php?id=' . $id ?>" autocomplete="off" method="POST">
            <p>
                <input name="contra" class="w3-input w3-border" maxlength="50" type="password" placeholder="Password" />
            </p>

            <p>
                <input name="confirmar_contra" class="w3-input w3-border" type="password" maxlength="80" placeholder="Confirmar password" />
            </p>

            <p>
                <button class="w3-button w3-blue w3-padding-large" type="submit">Guardar</button>
            </p>
        </form>
    </div>

</div>
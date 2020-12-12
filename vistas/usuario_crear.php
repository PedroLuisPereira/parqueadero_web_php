<!-- nuevo cliente -->
<div class="btn_nuevo">
    <a href="<?php echo URL_BASE . 'usuarios.php' ?>" class="w3-button w3-blue"> Atras</a>
</div>

<hr>

<div class="w3-modal-content">

    <!-- respuestas -->
    <?php if (isset($respuesta)) : ?>
        <div class="w3-panel w3-pale-green w3-border">
            <p><?php echo $respuesta ?></p>
        </div>
    <?php endif; ?>

    <header class="w3-container w3-light-grey">
        <h2>Nuevo Usuario</h2>
    </header>
    <div class="w3-container">

        <!-- errores de validacion  -->
        <?php foreach ($errores as $error) : ?>
            <span class="w3-text-red"><?php echo $error ?> </span><br>
        <?php endforeach; ?>


        <form action="<?php echo URL_BASE . 'usuarios_crear.php' ?>" autocomplete="off" method="POST">

            <p>
                <input name="nombre" class="w3-input w3-border" maxlength="50" type="text" placeholder="Nombre" />
            </p>

            <p>
                <input name="correo" class="w3-input w3-border" type="email" maxlength="80" placeholder="Correo" />
            </p>

            <p>
                <input name="contra" class="w3-input w3-border" type="password" maxlength="255" placeholder="Contra" />
            </p>

            <p>
                <select class="w3-select w3-border" name="rol">
                    <option value="" disabled selected>Rol</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Usuario">Usuario</option>
                </select>
            </p>

            <p>
                <button class="w3-button w3-blue w3-padding-large" type="submit">Guardar</button>
            </p>
        </form>
    </div>

</div>
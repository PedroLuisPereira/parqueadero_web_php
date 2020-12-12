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
        <h2>Nuevo Cliente</h2>
    </header>
    <div class="w3-container">

        <?php foreach ($errores as $error) : ?>
            <span class="w3-text-red"><?php echo $error ?> </span><br>
        <?php endforeach; ?>


        <form action="<?php echo URL_BASE . 'clientes_crear.php' ?>" method="POST">

            <p>
                <input name="numero_documento" class="w3-input w3-border" value="<?php echo $numero_documento ?>" maxlength="50" type="text" placeholder="Número Documento">
            </p>

            <p>
                <input name="nombre" class="w3-input w3-border" value="<?php echo $nombre ?>" type="text" maxlength="50" placeholder="Nombre">
            </p>

            <p>
                <input name="apellidos" class="w3-input w3-border" value="<?php echo $apellidos ?>" type="text" maxlength="50" placeholder="Apellidos">
            </p>

            <h4>Datos del vehículo</h4>
            <p>
                <input name="placa" class="w3-input w3-border" value="<?php echo $placa ?>" type="text" maxlength="50" placeholder="Placa - Serial">
            </p>

            <p>
                <select class="w3-select w3-border" name="tipo">
                    <option <?php if ($tipo == '') echo 'selected' ?> value="" disabled>Tipo</option>
                    <option <?php if ($tipo == 'Automovil') echo 'selected' ?> value="Automovil">Automóvil</option>
                    <option <?php if ($tipo == 'Moto') echo 'selected' ?> value="Moto">Moto</option>
                    <option <?php if ($tipo == 'Bicicleta') echo 'selected' ?> value="Bicicleta">Bicicleta</option>
                </select>
            </p>

            <p>
                <button class="w3-button w3-blue w3-padding-large" type="submit">Guardar</button>
            </p>
        </form>
    </div>
    
</div>
<div id="app">
    <!-- formulario -->
    <?php if (isset($respuesta)) : ?>
        <div class="w3-panel w3-pale-green w3-border">
            <p><?php echo $respuesta ?></p>
        </div>
    <?php endif; ?>

    <div class="formulario">
        <div class="w3-card-4">
            <div class="w3-container w3-dark-grey">
                <h2>Tarifas</h2>
            </div>

            <?php foreach ($errores as $error) : ?>
                <span class="w3-text-red"><?php echo $error ?> </span><br>
            <?php endforeach; ?>

            <form method="POST" class="w3-container">
                <p>
                    <label for="">Valor Minuto Automoviles</label>
                    <input name="minuto_autos" value="<?php echo $datos[0]["minuto_autos"] ?>" class="w3-input w3-border" type="number" min="0" step="0.01" placeholder="Valor Minuto Automoviles " required>
                </p>
                <p>
                    <label for="">Valor Minutos Motos</label>
                    <input name="minuto_motos" value="<?php echo $datos[0]["minuto_motos"] ?>" class="w3-input w3-border" type="number" min="0" step="0.01" placeholder="Valor Minutos Motos" required>
                </p>
                <p>
                    <label for="">Valor Minutos Bicicletas</label>
                    <input name="minuto_bicicletas" value="<?php echo $datos[0]["minuto_bicicletas"] ?>" class="w3-input w3-border" type="number" min="0" step="0.01" placeholder="Valor Minutos Bicicletas" required>
                </p>
                <h4>Descuentos</h4>
                <p>
                    <label for="">Minuto para obtener el descuento</label>
                    <input name="minutos" value="<?php echo $datos[0]["minutos"] ?>" class="w3-input w3-border" type="number" placeholder="Placa-Serial" min="0" required>
                </p>
                <p>
                    <label for="">Descuento %</label>
                    <input name="descuento" value="<?php echo $datos[0]["descuento"] ?>" class="w3-input w3-border" type="number" placeholder="Placa-Serial" min="0" step="0.01" required>
                </p>


                <p>
                    <button class="w3-button w3-blue w3-padding-large" type="submit">Guardar</button>
                </p>


            </form>
        </div>

    </div>
</div>
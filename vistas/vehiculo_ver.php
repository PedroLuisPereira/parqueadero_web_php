<!-- nuevo cliente -->
<div class="btn_nuevo">
    <a href="<?php echo URL_BASE . 'vehiculos.php' ?>" class="w3-button w3-blue"> Atras</a>
</div>

<hr>

<div class="w3-container">
    <form class="w3-container w3-card-4 w3-light-grey">
        <h2>Detalles del vehículo</h2>
        <p>
            <label>Placa</label>
            <input class="w3-input w3-border" value="<?Php echo $placa ?>" type="text" />
        </p>

        <p>
            <label>Tipo</label>
            <input class="w3-input w3-border" value="<?Php echo $tipo ?>" type="text" />
        </p>
    </form>
</div>
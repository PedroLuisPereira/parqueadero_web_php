<!-- nuevo cliente -->
<div class="w3-row">
    <div class="w3-col m4 l3">
        <div class="btn_nuevo">
        <a href="clientes_crear.php" class="w3-button w3-green">Exportar a Excel</a>
        </div>
    </div>

    <!-- formulario buscar -->
    <form>
        <div class="w3-col m7 l8">
            <div>
                <input class="w3-input w3-border" type="search" name="buscar" value="<?php echo $buscar ?> " id="" />
            </div>
        </div>
        <div class="w3-col m1 l1">
            <div>
                <button type="submit" class="w3-button w3-border w3-blue"> Buscar </button>
            </div>
        </div>
    </form>
</div>


<hr>

<!-- listado de clientes -->
<div class="tabla_clientes">
    <h2>Servicios</h2>
    <div class="w3-responsive">
        <table class="w3-table-all">
            <tr class="w3-dark-grey">
                <th>Placa</th>
                <th>Parqueadero</th>
                <th>Estado</th>
                <th>Hora entrada</th>
                <th>Hora salida</th>
                <th>Minutos</th>
                <th>Valor minuto</th>
                <th>Total </th>
            </tr>
            <?php foreach ($datos as $dato) : ?>
                <tr>
                    <td><?php echo $dato["placa"] ?></td>
                    <td><?php echo $dato["parqueadero"] ?></td>
                    <td><?php echo $dato["estado"] ?> </td>
                    <td><?php echo $dato["hora_entrada"] ?> </td>
                    <td><?php echo $dato["hora_salida"] ?> </td>
                    <td><?php echo $dato["minutos"] ?> </td>
                    <td><?php echo $dato["valor_minuto"] ?></td>
                    <td><?php echo $dato["total"] ?> </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="w3-bar">
        <a href="#" class="w3-button">&laquo;</a>
        <a href="#" class="w3-button w3-blue">1</a>
        <a href="#" class="w3-button">2</a>
        <a href="#" class="w3-button">3</a>
        <a href="#" class="w3-button">4</a>
        <a href="#" class="w3-button">&raquo;</a>
    </div>
<!-- nuevo cliente -->
<div class="w3-row">
    <div class="w3-col m4 l3">
        <div class="btn_nuevo">
            <a href="usuarios_crear.php" class="w3-button w3-blue"> + Nuevo Usuario</a>
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

<h2>Clientes Registrados</h2>
<div class="w3-responsive w3-margin-bottom">
    <table class="w3-table-all">
        <tr class="w3-dark-grey">
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Editar</th>
            <th>Password</th>
        </tr>

        <?php foreach ($datos as $dato) : ?>

            <tr>
                <td><?php echo $dato["nombre"] ?></td>
                <td><?php echo $dato["correo"] ?></td>
                <td><?php echo $dato["rol"] ?></td>
                <td><?php echo $dato["estado"] ?></td>
                <td>
                    <a href="<?php echo URL_BASE . 'usuarios_editar.php?id=' . $dato['id'] ?>" class="w3-button w3-highway-blue">Editar</a>
                </td>
                <td>
                    <a href="<?php echo URL_BASE . 'usuarios_password.php?id=' . $dato['id'] ?>" class="w3-button w3-highway-green"> Cambiar</a>
                </td>
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




<!-- formulario -->
<div class="formulario">
    <div class="w3-card-4">
        <div class="w3-container w3-dark-grey">
            <h3>Ingresar Vehículo</h3>
        </div>

        <form action="<?php echo URL_BASE . 'index.php?nuevo_servicio' ?>" method="POST" class="w3-container">
            <!-- respuesta -->
            <?php if (isset($respuestas)) : ?>
                <div class="w3-panel w3-pale-green w3-border">
                    <p><?php echo $respuestas ?></p>
                </div>
            <?php endif; ?>
            <!-- errores -->
            <?php foreach ($errores as $error) : ?>
                <span class="w3-text-red"><?php echo $error ?> </span><br>
            <?php endforeach; ?>

            <p>
                <label class="w3-text-grey"><b>Placa - Serial del vehículo</b></label>
                <a class="btn_eliminar" href="<?php echo URL_BASE . 'clientes_crear.php' ?>">Registrar</a>
                <input class="w3-input w3-border" id="placa" required maxlength="50" name="placa" type="text" />
            </p>

            <div id="respuesta">
                <h5 id="campo"></h5>
            </div>
            <div id="opciones">
                <p>
                    <label class="w3-text-grey"><b>Seleccione parqueadero</b></label>
                    <select class="w3-select w3-border" name="parqueadero" required id="select"> </select>
                </p>
                <p>
                    <button class="w3-btn w3-blue">Ingresar vehículo</button>
                </p>
            </div>
        </form>
    </div>
</div>


<!-- parqueadero -->
<br />
<div class="w3-card-4 parqueadero">
    <div class="w3-container w3-dark-grey">
        <h3>Parqueaderos</h3>
    </div>

    <div class="autos">
        <?php foreach ($datos['automoviles'] as $dato) : ?>

            <?php if ($dato['estado'] == 'Disponible') : ?>
                <div class="cubiculo">
                    <span> <?php echo $dato['parqueadero'] ?></span>
                </div>
            <?php else : ?>
                <div class="tooltip cubiculoOcupado">
                    <span> Placa: <?php echo $dato['placa'] ?> </span>
                    <div class="tooltiptext">
                        <p>Parqueadero: <?php echo $dato['parqueadero'] ?> </p>
                        <p>Nombre: <?php echo $dato['cliente']['nombre'] ?> </p>
                        <p>Apellidos: <?php echo $dato['cliente']['apellidos'] ?> </p>
                        <p>N° Documento: <?php echo $dato['cliente']['numero_documento'] ?></p>
                        <hr />
                        <input type="button" id="mover" onclick="abrir_modal('<?php echo $dato['tipo'] ?>' , '<?php echo $dato['parqueadero'] ?>')" class="w3-button w3-blue" value="Mover" />
                        <hr />
                        <form action="<?php echo URL_BASE . 'index.php?terminar_servicio' ?>" method="POST">
                            <input type="hidden" name="placa" value="<?php echo $dato['placa'] ?>">
                            <input type="submit" value="Terminar servicio" class="w3-button w3-red" />
                        </form>
                    </div>
                </div>

            <?php endif; ?>

        <?php endforeach; ?>
        <div class="restaurar"></div>
    </div>

    <div class="bicicletas">
        <?php foreach ($datos['bicicletas'] as $dato) : ?>

            <?php if ($dato['estado'] == 'Disponible') : ?>
                <div class="cubiculo">
                    <span> <?php echo $dato['parqueadero'] ?></span>
                </div>
            <?php else : ?>
                <div class="tooltip cubiculoOcupado">
                    <span> Placa: <?php echo $dato['placa'] ?> </span>
                    <div class="tooltiptext">
                        <p>Parqueadero: <?php echo $dato['parqueadero'] ?> </p>
                        <p>Nombre: <?php echo $dato['cliente']['nombre'] ?> </p>
                        <p>Apellidos: <?php echo $dato['cliente']['apellidos'] ?> </p>
                        <p>N° Documento: <?php echo $dato['cliente']['numero_documento'] ?></p>
                        <hr />
                        <input type="button" id="mover" onclick="abrir_modal('<?php echo $dato['tipo'] ?>' , '<?php echo $dato['parqueadero'] ?>')" class="w3-button w3-blue" value="Mover" />
                        <hr />
                        <form action="<?php echo URL_BASE . 'index.php?terminar_servicio' ?>" method="POST">
                            <input type="hidden" name="placa" value="<?php echo $dato['placa'] ?>">
                            <input type="submit" value="Terminar servicio" class="w3-button w3-red" />
                        </form>
                    </div>
                </div>

            <?php endif; ?>

        <?php endforeach; ?>
        <div class="restaurar"></div>
    </div>

    <div class="motos">
        <?php foreach ($datos['motos'] as $dato) : ?>

            <?php if ($dato['estado'] == 'Disponible') : ?>
                <div class="cubiculo">
                    <span> <?php echo $dato['parqueadero'] ?></span>
                </div>
            <?php else : ?>
                <div class="tooltip cubiculoOcupado">
                    <span> Placa: <?php echo $dato['placa'] ?> </span>
                    <div class="tooltiptext">
                        <p>Parqueadero: <?php echo $dato['parqueadero'] ?> </p>
                        <p>Nombre: <?php echo $dato['cliente']['nombre'] ?> </p>
                        <p>Apellidos: <?php echo $dato['cliente']['apellidos'] ?> </p>
                        <p>N° Documento: <?php echo $dato['cliente']['numero_documento'] ?></p>
                        <hr />
                        <input type="button" id="mover" onclick="abrir_modal('<?php echo $dato['tipo'] ?>' , '<?php echo $dato['parqueadero'] ?>')" class="w3-button w3-blue" value="Mover" />
                        <hr />
                        <form action="<?php echo URL_BASE . 'index.php?terminar_servicio' ?>" method="POST">
                            <input type="hidden" name="placa" value="<?php echo $dato['placa'] ?>">
                            <input type="submit" value="Terminar servicio" class="w3-button w3-red" />
                        </form>
                    </div>
                </div>

            <?php endif; ?>

        <?php endforeach; ?>
        <div class="restaurar"></div>
    </div>
</div>

<!-- Modal mover  -->
<div id="id01" class="w3-modal">
    <div class="w3-modal-content">
        <header class="w3-container w3-light-grey">
            <span id="cerrar_modal" class="w3-button w3-display-topright">&times;</span>
            <h2>Nuevo Parqueadero</h2>
        </header>
        <div class="w3-container">
            <form action="<?php echo URL_BASE . 'index.php?mover' ?>" method="POST">
                <p>
                    <label class="w3-text-grey"><b>Parqueadero</b></label>
                    <input type="hidden" id="parqueadero_viejo" name="parqueadero_viejo">
                    <select class="w3-select w3-border" required id="parqueadero_nuevo" name="parqueadero_nuevo">
                    </select>
                </p>
                <p>
                    <button class="w3-btn w3-blue">Mover vehículo</button>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
    //establecer evento de tecla al campo placa
    document.getElementById("placa").addEventListener("keyup", consultar);
    //ocultar respuestas
    document.getElementById('respuesta').style.display = 'none';
    document.getElementById('opciones').style.display = 'none';

    function consultar() {
        //obtener valor de la placa
        var placa = toUpperCase(document.getElementById("placa").value);
        var respuesta = document.getElementById("respuesta");
        var opciones = document.getElementById("opciones");
        var mensaje = '';

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var json = JSON.parse(this.responseText);
                if (placa.length > 0) {
                    if (json.mensaje == null) {
                        document.getElementById('respuesta').style.display = 'none';
                        document.getElementById('opciones').style.display = 'block';
                        var select = document.getElementById("select");
                        var datos = json.datos;
                        for (var i = 0; i < datos.length; i++) {
                            select.options[i] = new Option(datos[i].parqueadero);
                        }
                    } else {
                        document.getElementById('respuesta').style.display = 'block';
                        document.getElementById('campo').innerHTML = json.mensaje;
                        document.getElementById('opciones').style.display = 'none';
                    }

                } else {
                    document.getElementById('respuesta').style.display = 'none';
                }

            }
        };
        xhttp.open("GET", "index.php?placa=" + placa, true);
        xhttp.send();
    }

    //mover vehiculo
    document.getElementById("cerrar_modal").addEventListener("click", cerrar_modal);
    var parqueadero_viejo = '';

    function abrir_modal(tipo, parqueadero) {
        var url = "index.php?tipo=" + tipo;
        var solicitud = new XMLHttpRequest();
        solicitud.addEventListener("load", llenar_select);
        solicitud.open("GET", url, true);
        solicitud.send(null);
        parqueadero_viejo = parqueadero;

    }

    function llenar_select(evento) {
        var datos = evento.target;
        if (datos.status == 200) {
            var parqueadero_nuevo = document.getElementById("parqueadero_nuevo");
            var json = JSON.parse(datos.responseText);
            var datos = json.datos;
            for (var i = 0; i < datos.length; i++) {
                parqueadero_nuevo.options[i] = new Option(datos[i].parqueadero);
            }
        }
        document.getElementById('id01').style.display = 'block';
        document.getElementById("parqueadero_viejo").value = parqueadero_viejo;
    }

    function cerrar_modal() {
        document.getElementById('id01').style.display = 'none';
    }
</script>
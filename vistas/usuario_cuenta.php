<div class="w3-content w3-margin-top" style="max-width: 1400px">
    <!-- The Grid -->
    <div class="w3-row-padding">
        <!-- Left Column -->
        <div class="w3-third">
            <div class="w3-white w3-text-grey w3-card-4">
                <div class="w3-display-container">
                    <img src="<?php echo URL_BASE . 'public/img/avatar.jpg' ?> " style="width: 100%" alt="Avatar" />
                    <div class="w3-display-bottomleft w3-container w3-text-black"></div>
                </div>
            </div>

            <!-- End Left Column -->
        </div>

        <!-- Right Column -->
        <div class="w3-twothird">
            <div class="w3-container w3-card w3-white w3-margin-bottom">
                <div class="w3-container">
                    <h2><?php echo $_SESSION["usuario_nombre"] ?></h2>
                    <p>
                        <i class="fa fa-envelope fa-fw w3-margin-right w3-large w3-text-teal"></i>
                        <?php echo $_SESSION["usuario_correo"] ?>
                    </p>
                    <p>
                        <i class="fa fa-briefcase fa-fw w3-margin-right w3-large w3-text-teal"></i>
                        <?php echo $_SESSION["usuario_rol"] ?>
                    </p>
                    <hr />
                    <p>
                        <a href="<?php echo URL_BASE . 'logout.php' ?>" class="w3-button w3-red">Cerrar sesión</a>
                    </p>
                </div>
            </div>

            <div class="w3-container w3-card w3-white w3-margin-bottom">
                <h3 class="w3-text-grey w3-padding-16">
                    <i class="fa fa-certificate fa-fw w3-margin-right w3-xxlarge w3-text-teal"></i>Agregar avatar
                </h3>

                <form action="">
                    <p>
                        <input class="w3-input w3-border" type="file" name="" id="" />
                    </p>
                    <p>
                        <button type="submit" class="w3-button w3-border w3-blue">
                            Agregar
                        </button>
                    </p>
                </form>
                <hr />
            </div>

            <div class="w3-container w3-card w3-white w3-margin-bottom">
                <h3 class="w3-text-grey w3-padding-16">
                    <i class="fa fa-certificate fa-fw w3-margin-right w3-xxlarge w3-text-teal"></i>Actualizar password
                </h3>

                <div class="w3-container">
                    <?php if (isset($respuesta)) : ?>
                        <div class="w3-panel w3-pale-green w3-border">
                            <p><?php echo $respuesta ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- errores de validacion  -->
                    <?php foreach ($errores as $error) : ?>
                        <span class="w3-text-red"><?php echo $error ?> </span><br>
                    <?php endforeach; ?>
                </div>


                <form action="<?php echo URL_BASE . 'usuarios_cuenta.php?password' ?>" method="POST">
                    <p>
                        <label for="">Nueva password</label>
                        <input name="contra" class="w3-input w3-border" type="password" placeholder="Password" />
                    </p>
                    <p>
                        <label for="">Confirmar password</label>
                        <input name="confirmar_contra" class="w3-input w3-border" type="password" placeholder="Confirmar password" />
                    </p>

                    <p>
                        <button type="submit" class="w3-button w3-border w3-blue">
                            Guardar
                        </button>
                    </p>
                </form>
            </div>
            <!-- End Right Column -->
        </div>
    </div>
</div>
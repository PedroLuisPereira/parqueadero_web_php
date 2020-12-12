<!DOCTYPE html>
<html>

<head>
    <title>Parqueadero</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="public/img/logo.jpg" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="public/css/estilos.css">

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-highway.css">
    <style>
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: "Raleway", Arial, Helvetica, sans-serif
        }
    </style>
</head>

<body>

    <!-- formulario -->
    <div class="login">
        <div class="w3-card-4">
            <div class="w3-container w3-dark-grey">
                <h3>Login</h3>
            </div>

            <div class="w3-container">
                <br>
                <?php foreach ($errores as $error) : ?>
                    <span class="w3-text-red"><?php echo $error ?> </span><br>
                <?php endforeach; ?>

                <?php if (isset($respuesta)) : ?>
                    <span class="w3-text-red"><?php echo $respuesta ?> </span><br>
                <?php endif; ?>

            </div>

            <form class="w3-container" action="<?php echo URL_BASE . 'login.php' ?>" method="POST">
                <p>
                    <input class="w3-input w3-padding-16 w3-border" name="correo" type="email" placeholder="Name" />
                </p>
                <p>
                    <input class="w3-input w3-padding-16 w3-border" name="contra" type="password" placeholder="Password" />
                </p>
                <p>
                    <button class="w3-button w3-blue w3-padding-large" type="submit">
                        Ingresar
                    </button>
                </p>
            </form>
        </div>
    </div>

</body>

</html>
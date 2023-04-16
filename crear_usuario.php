<!DOCTYPE html>
<html>

<head>
	<title>Nuevo usuario</title>
	<meta charset="utf-8">
	<?php
	include 'includes/mysql.php';
	?>
</head>

<body style="background-color: grey">

    <h1 style="text-align:center;">PANTALLA DE CREACION DE USUARIO</h1><hr>
    <p>Nuevo usuario:</p>
    <form method="post" action="" >
        <label for="idUsuario">Usuario:</label><input type="text" id="idUsuario" name="usuario" minlength="3" title="Al menos 3 caracteres" required>
        <br><br><label for="idClave">Clave:</label><input type="password" id="idClave" name="clave" minlength="3" title="Al menos 3 caracteres" required>
        <br><br><label for="idEmail">Email:</label><input type="email" id="idEmail" name="email" minlength="3" required>
        <br><br><label for="idTelf">Telefono:</label><input type="text" id="idTelf" name="telefono" pattern="[0-9]{9}" title="Se requieren 9 dígitos" required>
        <br><br><input type="submit" value="Crear usuario" name="nuevoUsuario"><br>
    </form>

    <?php
    if(isset($_POST["nuevoUsuario"])){

        $usuario = $_POST["usuario"];
        $compruebaNombre = $pdo->prepare("SELECT nombre FROM usuario where nombre = ?");
        $compruebaNombre->execute([$usuario]);
        $resultadoNombre = $compruebaNombre->fetch();

        if(empty($resultadoNombre)){
            // hasheamos constraseña con crypt_blowfish
            $campo_salt = "$2y$07$" . $_POST["usuario"] . $_POST["telefono"];
            if(strlen($campo_salt) < 29){
                $campo_salt = $campo_salt . $_POST["usuario"] . $_POST["telefono"];
            }
            
            $crypt_blowfish = crypt($_POST["clave"], $campo_salt);

            $insertUsuario = "INSERT INTO usuario (nombre, clave) VALUES (?, ?)";
            $pdo->prepare($insertUsuario)->execute([$usuario, $crypt_blowfish]);

            $obtenerId = $pdo->prepare("SELECT idUsuario FROM usuario where nombre = ?");
            $obtenerId->execute([$usuario]);
            $resultadoId = $obtenerId->fetch();

            $insertDatos = $pdo->prepare("INSERT INTO datosUsu VALUES (?, ?, ?)");
            $insertDatos->execute([$resultadoId[0], $_POST["email"], $_POST["telefono"]]);

            echo "<center style=\"color:purple; font-size:2em;\"> Usuario \"" . $usuario . "\" satisfactoriamente creado</center>";
        } else {
            echo "<center style=\"color:yellow; font-size:2em;\">\"" . $resultadoNombre[0] .  "\" existe, utilize otro nombre de usuario.</center>";
        }
    }
    ?>

    <hr><br>
	<br><a href="login.php">Volver a login</a><br><br>
</body>

</html>
<?php
    include("registro.php");

    $sql = "SELECT * FROM usuario WHERE cod = ?";
    $stmt = $con->prepare($sql);

    if ($stmt)
    {
        $stmt->bind_param("s", $id);

        $stmt->execute();

        $stmt->bind_result($id, $nombre, $usuario, $password, $email);

        $stmt->fetch();

        $stmt->close();
    }
    else {
        echo "Error en la sentencia SQL";
    }

    $con->close();
?>
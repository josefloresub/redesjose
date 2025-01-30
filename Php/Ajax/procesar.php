<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clave = $_POST["clave"];
    $md5 = md5($clave);
    $sha1 = sha1($clave);

    echo "Clave: $clave <br>";
    echo "Clave encriptada en md5: $md5 <br>";
    echo "Clave encriptada en sha1: $sha1";
}
?>

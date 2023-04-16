<?php
session_start();
if ($_SESSION["credencialesCorrectas"] == false){
    $_SESSION["sinLogueo"] = true;
    header("Location: login.php");
}
?>

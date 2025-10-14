<?php 
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();
//eliminacion de la persona
 $id=$_GET['id'];
    //consulta para traer los datos 
 


    $estado='Inactivo';
        $mysql->efectuarConsulta("UPDATE usuario 
            SET 
                estado='$estado'             WHERE id_usuario='$id'");
        $mysql->desconectar();

        header('location:../index.php');
        exit();
?>

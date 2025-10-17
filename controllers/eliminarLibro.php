<?php 
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();
//eliminacion de la persona
 $id=$_GET['id'];
    //consulta para traer los datos 
 


    $estado='Inactivo';
        $mysql->efectuarConsulta("UPDATE libro
            SET 
                disponibilidad_libro='$estado'             WHERE id_libro='$id'");
        $mysql->desconectar();

        header('location:../views/inventario.php');
        exit();
?>

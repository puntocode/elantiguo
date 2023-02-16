<?php

/**
 * Created by PhpStorm.
 * User: FedeXavier
 * Date: 03/05/2016
 * Time: 01:12 PM
 */
class Selectores
{

    public function cargarEmpleados(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT idempleado, nombre FROM empleado order by nombre asc;");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function cargarStock(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT idstock, CONCAT(producto, ' - E:', cantidad) producto, producto 'stock', costo FROM stock order by producto asc;");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function cargarClientes(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT idcliente, cliente FROM cliente order by cliente asc;");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function uploadProvider(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT idproveedor,proveedor FROM proveedor order by proveedor asc;");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function uploadCategory(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT idcategoria,categoria FROM categoria order by categoria asc;");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function uploadProducto(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT idproducto,producto FROM producto order by producto asc;");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function returnRol(){
        $userAr['iduser'] = $_SESSION['session']['iduser'];
        $userAr['idrol'] = $_SESSION['session']['idrol'];
        $userAr['imagen'] = $_SESSION['session']['imagen'];
        $userAr['user'] = $_SESSION['session']['user'];
        return $userAr;
    }


    public function cargarBruto(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT idstock, CONCAT(producto, ' - E:', cantidad) producto FROM stock
                                      WHERE producto like '%bruto%' order by producto asc;");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function cargarFetchAll($sentencia){
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function cargarFetch($sentencia){
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }
   
    /** Actual month last day **/
    public function lastDay() { 
        $month = date('m');
        $year = date('Y');
        $day = date("d", mktime(0,0,0, $month+1, 0, $year));
        return date('Y-m-d', mktime(0,0,0, $month, $day, $year));
    }
 
    /** Actual month first day **/
    function firstDay() {
        $month = date('m');
        $year = date('Y');
        return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
    }



}
<?php

/**
 * Created by PhpStorm.
 * User: Fede
 * Date: 10/07/2017
 * Time: 06:43 PM
 */
class Activo{

    private $idActivo;
    private $fecha;
    private $nroFactura;
    private $idCliente;
    private $estado;
    private $tipoIngress;

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return mixed
     */
    public function getTipoIngress()
    {
        return $this->tipoIngress;
    }

    /**
     * @param mixed $tipoIngress
     */
    public function setTipoIngress($tipoIngress)
    {
        $this->tipoIngress = $tipoIngress;
    }


    /**
     * @return mixed
     */
    public function getIdActivo()
    {
        return $this->idActivo;
    }

    /**
     * @param mixed $idActivo
     */
    public function setIdActivo($idActivo)
    {
        $this->idActivo = $idActivo;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getNroFactura()
    {
        return $this->nroFactura;
    }

    /**
     * @param mixed $nroFactura
     */
    public function setNroFactura($nroFactura)
    {
        $this->nroFactura = $nroFactura;
    }



    /**
     * @return mixed
     */
    public function getIdCliente()
    {
        return $this->idCliente;
    }

    /**
     * @param mixed $idCliente
     */
    public function setIdCliente($idCliente)
    {
        $this->idCliente = $idCliente;
    }


    /**
     * @param $mes
     * @param $anho
     * @return array
     */
    public function ingressMonth($mes, $anho){
        $sentencia = "SELECT  a.idactivo, a.fecha, c.cliente, a.nro_factura, SUM(d.total) total, a.estado, a.tipo_ingress
                    FROM activo a, detalle_activo d, cliente c
                    WHERE a.idactivo = d.idactivo and a.idcliente=c.idcliente 
                    AND MONTH(a.fecha)=$mes and YEAR(a.fecha)=$anho
                    AND a.estado=:estado
                    GROUP BY idactivo
                    ORDER BY a.fecha;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('estado' => $this->getEstado()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function recibosMes($mes, $anho){
        $sentencia = "SELECT  a.idactivo, a.fecha, c.cliente, a.nro_factura, d.total, d.producto, a.tipo_ingress
                    FROM activo a, detalle_activo d, cliente c
                    WHERE a.idactivo = d.idactivo and a.idcliente=c.idcliente 
                    AND MONTH(a.fecha)=$mes and YEAR(a.fecha)=$anho
                    AND a.estado='R'
                    ORDER BY a.fecha;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function sumTotal($mes, $anho){
        $sentencia = "SELECT a.tipo_ingress, SUM(d.total) total
                      FROM activo a, detalle_activo d
                      WHERE a.idactivo=d.idactivo
                      AND MONTH(a.fecha)=$mes and YEAR(a.fecha)=$anho
                      AND a.estado=:estado 
                      GROUP BY tipo_ingress;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('estado' => $this->getEstado()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function insertActivo(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO activo(fecha, nro_factura, estado, tipo_ingress, idcliente) VALUES (:fecha,:nro_factura,:estado,:tipo_ingress,:idcliente);");
        $query->execute(array('fecha' => $this->getFecha(),
            'nro_factura' => $this->getNroFactura(),
            'estado' => $this->getEstado(),
            'tipo_ingress' => $this->getTipoIngress(),
            'idcliente' => $this->getIdCliente()));
        $conexion = null;
        return $query->rowCount();
    }

    public function insertActivoLastId(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO activo(fecha, nro_factura, estado, tipo_ingress, idcliente) VALUES (:fecha,:nro_factura,:estado,:tipo_ingress,:idcliente);");
        $query->execute(array('fecha' => $this->getFecha(),
            'nro_factura' => $this->getNroFactura(),
            'estado' => $this->getEstado(),
            'tipo_ingress' => $this->getTipoIngress(),
            'idcliente' => $this->getIdCliente()));
        $id = $conexion->lastInsertId();
        $conexion = null;
        return $id;
    }

    public function selectMaxIdActivo(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT max(idactivo) codigo FROM activo;");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function selectFactura(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT a.idactivo, a.fecha, a.idcliente, c.cliente, a.nro_factura, a.estado, a.tipo_ingress
                    FROM activo a, detalle_activo d, cliente c
                    WHERE a.idactivo = d.idactivo and a.idcliente=c.idcliente
                    AND a.idactivo=:id");
        $query->execute(array('id' => $this->getIdActivo()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function anularActivo(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE activo
                                    SET tipo_ingress = :tipo
                                    WHERE idactivo = :id;");
        $query->execute(array('tipo' => $this->getTipoIngress(),
            'id' => $this->getIdActivo()));
        $conexion = null;
        return $query->rowCount();
    }

    public function updateActivo(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE activo
                                    SET fecha=:fecha, nro_factura=:nrofactura, idcliente=:idcliente, tipo_ingress=:tipo_ingreso 
                                    WHERE idactivo = :id;");
        $query->execute(array('fecha' => $this->getFecha(),
            'nrofactura' => $this->getNroFactura(),
            'idcliente' => $this->getIdCliente(),
            'tipo_ingreso' => $this->getTipoIngress(),
            'id' => $this->getIdActivo()));
        $conexion = null;
        return $query->rowCount();
    }

    public function selectRecibo(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT  a.idactivo, a.fecha, a.idcliente, c.cliente, a.nro_factura, d.total, d.producto, a.estado, a.tipo_ingress
                    FROM activo a, detalle_activo d, cliente c
                    WHERE a.idactivo = d.idactivo and a.idcliente=c.idcliente 
                    AND a.idactivo=:id");
        $query->execute(array('id' => $this->getIdActivo()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }
    
    
    /*Suma de totales por tipo Ingreso*/
    public function sumporTipoIngres($mes, $anho){
        $sentencia = "SELECT SUM(d.total) total
                      FROM activo a, detalle_activo d
                      WHERE a.idactivo=d.idactivo
                      AND MONTH(a.fecha)=$mes and YEAR(a.fecha)=$anho
                      AND a.estado=:estado AND a.tipo_ingress=:tipo;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('estado' => $this->getEstado(),
            'tipo'=>$this->getTipoIngress()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    /**
     * @return array resultado busqueda por cliente
     */
    public function searchCustomer(){
        $customer= "%".$this->getEstado()."%";
        $sentencia = "SELECT a.fecha, a.nro_factura, a.estado, c.cliente, d.cantidad, d.costo, d.total, d.producto
                    FROM activo a, cliente c, detalle_activo d
                    WHERE a.idactivo=d.idactivo AND a.idcliente=c.idcliente AND a.tipo_ingress='M'
                    AND cliente like :cliente;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('cliente' => $customer));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    /**
     * @return array resultado busqueda por producto
     */
    public function searchProduct(){
        $product= "%".$this->getEstado()."%";
        $sentencia = "SELECT a.fecha, a.nro_factura, a.estado, c.cliente, d.cantidad, d.costo, d.total, d.producto
                    FROM activo a, cliente c, detalle_activo d
                    WHERE a.idactivo=d.idactivo AND a.idcliente=c.idcliente AND a.tipo_ingress='M'
                    AND producto like :producto;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('producto' => $product));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }
}
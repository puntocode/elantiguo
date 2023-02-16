<?php

/**
 * Created by PhpStorm.
 * User: Fede
 * Date: 30/5/2017
 * Time: 5:12 PM
 */
class DetallePedido{

    private $idDetallePedido;
    private $detalle;
    private $cantidad;
    private $costo;
    private $idPedidos;

    /**
     * @return mixed
     */
    public function getIdDetallePedido()
    {
        return $this->idDetallePedido;
    }

    /**
     * @param mixed $idDetallePedido
     */
    public function setIdDetallePedido($idDetallePedido)
    {
        $this->idDetallePedido = $idDetallePedido;
    }

    /**
     * @return mixed
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * @param mixed $detalle
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;
    }

    /**
     * @return mixed
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return mixed
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * @param mixed $costo
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;
    }

    /**
     * @return mixed
     */
    public function getIdPedidos()
    {
        return $this->idPedidos;
    }

    /**
     * @param mixed $idPedidos
     */
    public function setIdPedidos($idPedidos)
    {
        $this->idPedidos = $idPedidos;
    }


    public function selectPedido(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT iddetalle_pedido,idpedidos,detalle,cantidad,costo,(cantidad*costo) total FROM detalle_pedido WHERE idpedidos=:idpedidos;");
        $query->execute(array('idpedidos' => $this->getIdPedidos()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function selectTotalProductos(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT SUM((cantidad*costo)) total FROM detalle_pedido WHERE idpedidos=:idpedidos");
        $query->execute(array('idpedidos' => $this->getIdPedidos()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function insertDetailOrder(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO detalle_pedido(detalle, cantidad, costo, idpedidos)
                                     VALUES(:detalle,:cantidad,:costo,:idpedidos);");
        $query->execute(array('detalle' => $this->getDetalle(),
            'cantidad' => $this->getCantidad(),
            'costo' => $this->getCosto(),
            'idpedidos' => $this->getIdPedidos()));
        $id = $conexion->lastInsertId();
        $conexion = null;
        return $id;
    }

    public function deleteDetailOrder(){
        $conexion = new Conexion();
        $query = $conexion->prepare("DELETE FROM detalle_pedido
                                     WHERE iddetalle_pedido=:id;");
        $query->execute(array('id' => $this->getIdDetallePedido()));
        $row=$query->rowCount();
        $conexion = null;
        return $row;
    }


}
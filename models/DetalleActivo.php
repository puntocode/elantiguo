<?php

/**
 * Created by PhpStorm.
 * User: Fede
 * Date: 18/07/2017
 * Time: 06:33 PM
 */
class DetalleActivo{
    
    private $idDetalleActivo;
    private $idActivo;
    private $cantidad;
    private $costo;
    private $producto;
    private $total;

    /**
     * @return mixed
     */
    public function getIdDetalleActivo()
    {
        return $this->idDetalleActivo;
    }

    /**
     * @param mixed $idDetalleActivo
     */
    public function setIdDetalleActivo($idDetalleActivo)
    {
        $this->idDetalleActivo = $idDetalleActivo;
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
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * @param mixed $producto
     */
    public function setProducto($producto)
    {
        $this->producto = $producto;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    public function selectDetalleFactura(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT iddetalle_activo, idactivo, cantidad, costo, producto, total FROM detalle_activo WHERE idactivo=:id");
        $query->execute(array('id' => $this->getIdActivo()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function insertDetalleActivo(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO detalle_activo(idactivo, cantidad, costo, producto, total) VALUES (:idactivo,:cantidad,:costo,:producto,:total);");
        $query->execute(array('idactivo' => $this->getIdActivo(),
            'cantidad' => $this->getCantidad(),
            'costo' => $this->getCosto(),
            'producto' => $this->getProducto(),
            'total' => $this->getTotal()));
        $conexion = null;
        return $query->rowCount();
    }

    public function delDetalleActivo(){
        $conexion = new Conexion();
        $query = $conexion->prepare("DELETE FROM detalle_activo WHERE iddetalle_activo=:id;");
        $query->execute(array('id' => $this->getIdDetalleActivo()));
        $conexion = null;
        return $query->rowCount();
    }


    /**
     * @return int
     */
    public function updateDetalleActivo(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE detalle_activo
                                    SET costo=:costo, producto=:producto, total=:total
                                    WHERE idactivo = :id;");
        $query->execute(array('costo' => $this->getCosto(),
            'producto' => $this->getProducto(),
            'total' => $this->getTotal(),
            'id' => $this->getIdActivo()));
        $conexion = null;
        return $query->rowCount();
    }
    
    

}
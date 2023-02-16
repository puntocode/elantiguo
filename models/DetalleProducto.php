<?php

/**
 * Created by PhpStorm.
 * User: FedeXavier
 * Date: 05/05/2017
 * Time: 04:12 PM
 */
class DetalleProducto
{

    private $idDetalleProducto;
    private $cantidad;
    private $idProducto;
    private $idStock;

    /**
     * @return mixed
     */
    public function getIdDetalleProducto()
    {
        return $this->idDetalleProducto;
    }

    /**
     * @param mixed $idDetalleProducto
     */
    public function setIdDetalleProducto($idDetalleProducto)
    {
        $this->idDetalleProducto = $idDetalleProducto;
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
    public function getIdProducto()
    {
        return $this->idProducto;
    }

    /**
     * @param mixed $idProducto
     */
    public function setIdProducto($idProducto)
    {
        $this->idProducto = $idProducto;
    }

    /**
     * @return mixed
     */
    public function getIdStock()
    {
        return $this->idStock;
    }

    /**
     * @param mixed $idStock
     */
    public function setIdStock($idStock)
    {
        $this->idStock = $idStock;
    }


    public function selectDetalleProducto(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT d.iddetalle_producto, p.producto, d.cantidad, s.producto material, s.costo, (d.cantidad*s.costo) total
                                     FROM detalle_producto d, producto p, stock s
                                     WHERE d.idproducto=p.idproducto AND d.idstock=s.idstock AND p.idproducto=:id;");
        $query->execute(array('id' => $this->getIdProducto()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    

    public function sumDetailProduc(){
        $sentencia = "SELECT d.idproducto, p.producto, sum(d.cantidad*s.costo) total
                      FROM detalle_producto d, producto p, stock s
                      WHERE d.idproducto=p.idproducto AND d.idstock=s.idstock AND d.idproducto=:id;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('id' => $this->getIdProducto()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function insertarDetalleProducto(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO detalle_producto(idproducto,cantidad,idstock)
                                    VALUES(:idproducto,:cantidad,:idstock);");
        $query->execute(array('idproducto' => $this->getIdProducto(),
            'cantidad' => $this->getCantidad(),
            'idstock' => $this->getIdStock()));
        return $query->rowCount();
        $conexion = null;
    }

    public function deleteDetalleProducto(){
        $conexion = new Conexion();
        $query = $conexion->prepare("DELETE FROM detalle_producto WHERE iddetalle_producto = :id;");
        $query->execute(array('id' => $this->getIdDetalleProducto()));
        return $query->rowCount();
        $conexion = null;
    }


}
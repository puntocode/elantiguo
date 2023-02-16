<?php

/**
 * Created by PhpStorm.
 * User: FedeXavier
 * Date: 23/02/2017
 * Time: 04:00 PM
 */
class Stock{

    private $idStock;
    private $codigo;
    private $producto;
    private $ubicacion;
    private $costo;
    private $stockMin;
    private $cantidad;
    private $unidad;

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

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
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
    public function getUbicacion()
    {
        return $this->ubicacion;
    }

    /**
     * @param mixed $ubicacion
     */
    public function setUbicacion($ubicacion)
    {
        $this->ubicacion = $ubicacion;
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
    public function getStockMin()
    {
        return $this->stockMin;
    }

    /**
     * @param mixed $stockMin
     */
    public function setStockMin($stockMin)
    {
        $this->stockMin = $stockMin;
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
    public function getUnidad()
    {
        return $this->unidad;
    }

    /**
     * @param mixed $unidad
     */
    public function setUnidad($unidad)
    {
        $this->unidad = $unidad;
    }

    public function verStock($limit){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT idstock,codigo,producto,ubicacion,costo,stock_min,cantidad,unidad FROM stock ORDER BY idstock DESC LIMIT $limit;");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function insertarStock(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO stock(codigo,producto,ubicacion,unidad,cantidad,stock_min,costo)
                                    VALUES(:codigo, :producto, :ubicacion, :unidad, :cantidad, :minimo, :costo);");
        $query->execute(array('codigo' => $this->getCodigo(),
            'producto' => $this->getProducto(),
            'ubicacion' => $this->getUbicacion(),
            'unidad' => $this->getUnidad(),
            'cantidad' => $this->getCantidad(),
            'minimo' => $this->getStockMin(),
            'costo' =>$this->getCosto()));
        return $query->rowCount();
        $conexion = null;
    }

    public function searchStock(){
        $conexion = new Conexion();
        $likeVar = "%" . $this->getProducto() . "%";
        $query = $conexion->prepare("SELECT idstock, producto, codigo, ubicacion, cantidad, stock_min, costo, unidad FROM stock
                                     WHERE producto LIKE :producto ORDER BY producto;");
        $query->execute(array('producto' => $likeVar ));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function selectStockId(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT idstock, producto, codigo, ubicacion, cantidad, stock_min, costo, unidad FROM stock WHERE idstock = :id;");
        $query->execute(array('id' => $this->getIdstock()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function retornaPorNombre(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT idstock,costo,cantidad FROM stock WHERE producto = :producto;");
        $query->execute(array('producto' => $this->getProducto()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function retornaMaxIdStock(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT MAX(idstock) codigo FROM stock;");
        $query->execute(array('producto' => $this->getProducto()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function updateStock(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE stock
                                    SET codigo = :codigo, producto = :producto, ubicacion = :ubicacion, costo = :costo,
                                    stock_min = :stock_min, unidad = :unidad, cantidad=:cantidad
                                    WHERE idstock = :id;");
        $query->execute(array('codigo' => $this->getCodigo(),
            'producto' => $this->getProducto(),
            'ubicacion' => $this->getUbicacion(),
            'costo' => $this->getCosto(),
            'stock_min' => $this->getStockMin(),
            'unidad' => $this->getUnidad(),
            'cantidad' => $this->getCantidad(),
            'id' => $this->getIdstock()));
        return $query->rowCount();
        $conexion = null;
    }

    public function deleteStock(){
        $conexion = new Conexion();
        $query = $conexion->prepare("DELETE FROM stock WHERE idstock=:id;");
        $query->execute(array('id' => $this->getIdstock()));
        return $query->rowCount();
        $conexion = null;
    }

    public function updateMovimiento(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE stock
                                    SET cantidad=:cantidad
                                    WHERE idstock = :id;");
        $query->execute(array('cantidad' => $this->getCantidad(),
            'id' => $this->getIdstock()));
        return $query->rowCount();
        $conexion = null;
    }

    public function updateCosto(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE stock
                                    SET costo=:costo
                                    WHERE idstock = :id;");
        $query->execute(array('costo' => $this->getCosto(),
            'id' => $this->getIdstock()));
        return $query->rowCount();
        $conexion = null;
    }



}
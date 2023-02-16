<?php

/**
 * Created by PhpStorm.
 * User: Fede
 * Date: 3/5/2017
 * Time: 10:56 AM
 */
class Producto
{

    private $idProducto;
    private $idCategoria;
    private $producto;
    private $valorVenta;
    private $imgSmall;
    private $imgBig;

    /**
     * @return mixed
     */
    public function getImgSmall()
    {
        return $this->imgSmall;
    }

    /**
     * @param mixed $imgSmall
     */
    public function setImgSmall($imgSmall)
    {
        $this->imgSmall = $imgSmall;
    }

    /**
     * @return mixed
     */
    public function getImgBig()
    {
        return $this->imgBig;
    }

    /**
     * @param mixed $imgBig
     */
    public function setImgBig($imgBig)
    {
        $this->imgBig = $imgBig;
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
    public function getIdCategoria()
    {
        return $this->idCategoria;
    }

    /**
     * @param mixed $idCategoria
     */
    public function setIdCategoria($idCategoria)
    {
        $this->idCategoria = $idCategoria;
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
    public function getValorVenta()
    {
        return $this->valorVenta;
    }

    /**
     * @param mixed $valorVenta
     */
    public function setValorVenta($valorVenta)
    {
        $this->valorVenta = $valorVenta;
    }



    public function selectProducto(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT p.idproducto, c.categoria, p.producto, p.valor_venta, p.img_small, p.img_big
                                     FROM producto p, categoria c
                                     WHERE p.idcategoria=c.idcategoria
                                     ORDER BY producto");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function selectMaxId(){
        $sentencia = "SELECT MAX(idproducto) codigo FROM producto;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function insertarProducto(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO producto(idcategoria,producto,valor_venta)
                                    VALUES(:idcategoria,:producto,:valor_venta);");
        $query->execute(array('idcategoria' => $this->getIdCategoria(),
            'producto' => $this->getProducto(),
            'valor_venta' => $this->getValorVenta()));
        return $query->rowCount();
        $conexion = null;
    }

    public function updateProducto(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE producto
                                    SET idcategoria=:idcategoria,producto=:producto,valor_venta=:valor_venta
                                    WHERE idproducto = :id;");
        $query->execute(array('idcategoria' => $this->getIdCategoria(),
            'producto' => $this->getProducto(),
            'valor_venta' => $this->getValorVenta(),
            'id' => $this->getIdProducto()));
        return $query->rowCount();
        $conexion = null;
    }

    public function updateImagen(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE producto
                                    SET img_small=:small,img_big=:big
                                    WHERE idproducto = :id;");
        $query->execute(array('small' => $this->getImgSmall(),
            'big' => $this->getImgBig(),
            'id' => $this->getIdProducto()));
        return $query->rowCount();
        $conexion = null;
    }

}
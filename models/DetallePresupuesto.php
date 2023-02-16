<?php

/**
 * Created by PhpStorm.
 * User: Fede
 * Date: 3/5/2017
 * Time: 10:56 AM
 */
class DetallePresupuesto{
    private $idDetallePresupuesto;
    private $cantidad;
    private $producto;
    private $costo;
    private $estado;
    private $idPresupuesto;


    /**
     * Get the value of idPresupuesto
     */ 
    public function getIdPresupuesto()
    {
        return $this->idPresupuesto;
    }

    /**
     * Set the value of idPresupuesto
     *
     * @return  self
     */ 
    public function setIdPresupuesto($idPresupuesto)
    {
        $this->idPresupuesto = $idPresupuesto;

        return $this;
    }

    /**
     * Get the value of estado
     */ 
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set the value of estado
     *
     * @return  self
     */ 
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get the value of costo
     */ 
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set the value of costo
     *
     * @return  self
     */ 
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get the value of producto
     */ 
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set the value of producto
     *
     * @return  self
     */ 
    public function setProducto($producto)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get the value of cantidad
     */ 
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set the value of cantidad
     *
     * @return  self
     */ 
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get the value of idDetallePresupuesto
     */ 
    public function getIdDetallePresupuesto()
    {
        return $this->idDetallePresupuesto;
    }

    /**
     * Set the value of idDetallePresupuesto
     *
     * @return  self
     */ 
    public function setIdDetallePresupuesto($idDetallePresupuesto)
    {
        $this->idDetallePresupuesto = $idDetallePresupuesto;

        return $this;
    }

    //--------------------------------------------------------End Getter and Setter---------------------------------------------------------------------------

    /**
     * get the result of the presupuesto table by estado
     */ 
    public function selectByIdpresupuesto(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT iddetalle_presupuesto,cantidad,producto,costo,estado,id_presupuesto
                                    FROM detalle_presupuesto
                                    WHERE id_presupuesto = :id;");
        $query->execute(array('id' => $this->getIdPresupuesto()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    /**
     * get the result of the detalle_presupuesto table by id_presupuesto and estado=activo
     */ 
    public function selectByIdpresupuestoActive(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT iddetalle_presupuesto,cantidad,producto,costo,estado,id_presupuesto
                                    FROM detalle_presupuesto
                                    WHERE id_presupuesto = :id and estado=1;");
        $query->execute(array('id' => $this->getIdPresupuesto()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }


     /**
     * insert the data in the table detalle_presupuesto
     */ 
    public function insertDetallePresupuesto(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO detalle_presupuesto(id_presupuesto, cantidad, costo, producto, estado) 
                                    VALUES (:id,:cantidad,:costo,:producto,:estado);");
        $query->execute(array(
            'id' => $this->getIdPresupuesto(),
            'cantidad' => $this->getCantidad(),
            'costo' => $this->getCosto(),
            'producto' => $this->getProducto(),
            'estado' => $this->getEstado()
        ));
        $conexion = null;
        return $query->rowCount();
    }

     /**
     * get the result of the presupuesto table by id 
     */ 
    public function selectSumTotalByIdPresupuesto(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT SUM(costo*cantidad) AS total 
                                    FROM detalle_presupuesto 
                                    WHERE id_presupuesto=:id and estado='1'; ");
        $query->execute(array('id' => $this->getIdPresupuesto()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

     /**
     * update estado from the table detalle_presupuesto by id_detalle_presupuesto
     */ 
    public function updateEstado(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE detalle_presupuesto
                                    SET estado = :estado
                                    WHERE iddetalle_presupuesto = :id;");
        $query->execute(array(
            'estado' => $this->getEstado(),
            'id' => $this->getIdDetallePresupuesto()
        ));
        $conexion = null;
        return $query->rowCount();
    }

    /**
     * get the result of the detalle_presupuesto table by id_detalle_presupuesto
     */ 
    public function selectByIdDetallePresupuesto(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT iddetalle_presupuesto,cantidad,producto,costo,estado,id_presupuesto
                                    FROM detalle_presupuesto
                                    WHERE iddetalle_presupuesto = :id;");
        $query->execute(array('id' => $this->getIdDetallePresupuesto()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }


    

}
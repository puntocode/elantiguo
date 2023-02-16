<?php

/**
 * Created by PhpStorm.
 * User: Fede
 * Date: 10/5/2017
 * Time: 3:49 PM
 */
class ManoObra{

    private $idManoObra;
    private $manoObra;
    private $costo;
    private $idProducto;

    /**
     * @return mixed
     */
    public function getIdManoObra()
    {
        return $this->idManoObra;
    }

    /**
     * @param mixed $idManoObra
     */
    public function setIdManoObra($idManoObra)
    {
        $this->idManoObra = $idManoObra;
    }

    /**
     * @return mixed
     */
    public function getManoObra()
    {
        return $this->manoObra;
    }

    /**
     * @param mixed $manoObra
     */
    public function setManoObra($manoObra)
    {
        $this->manoObra = $manoObra;
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

    
    public function selectManoDeObra(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT idmano_obra, mano_obra, costo
                                     FROM mano_obra
                                     WHERE idproducto=:id;");
        $query->execute(array('id' => $this->getIdProducto()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function sumManoObra(){
        $sentencia = "SELECT sum(costo) total
                      FROM mano_obra
                      WHERE idproducto=:id;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('id' => $this->getIdProducto()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function insertarManoObra(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO mano_obra(idproducto,mano_obra,costo)
                                    VALUES(:idproducto,:mano_obra,:costo);");
        $query->execute(array('idproducto' => $this->getIdProducto(),
            'mano_obra' => $this->getManoObra(),
            'costo' => $this->getCosto()));
        return $query->rowCount();
        $conexion = null;
    }

    public function deleteManoObra(){
        $conexion = new Conexion();
        $query = $conexion->prepare("DELETE FROM mano_obra WHERE idmano_obra = :id;");
        $query->execute(array('id' => $this->getIdManoObra()));
        return $query->rowCount();
        $conexion = null;
    }

}
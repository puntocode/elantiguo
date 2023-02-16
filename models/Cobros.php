<?php

/**
 * Created by PhpStorm.
 * User: Fede
 * Date: 6/6/2017
 * Time: 3:04 PM
 */
class Cobros{

    private $idCobro;
    private $fecha;
    private $cobro;
    private $idPedidos;
    private $obs;

    /**
     * @return mixed
     */
    public function getObs()
    {
        return $this->obs;
    }

    /**
     * @param mixed $obs
     */
    public function setObs($obs)
    {
        $this->obs = $obs;
    }

    /**
     * @return mixed
     */
    public function getIdCobro()
    {
        return $this->idCobro;
    }

    /**
     * @param mixed $idCobro
     */
    public function setIdCobro($idCobro)
    {
        $this->idCobro = $idCobro;
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
    public function getCobro()
    {
        return $this->cobro;
    }

    /**
     * @param mixed $cobro
     */
    public function setCobro($cobro)
    {
        $this->cobro = $cobro;
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

    public function selectCobro(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT idcobro,fecha,cobro,obs,idpedidos FROM cobros WHERE idpedidos=:idpedidos;");
        $query->execute(array('idpedidos' => $this->getIdPedidos()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function sumCobros(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT SUM(cobro) total FROM cobros WHERE idpedidos=:idpedidos;");
        $query->execute(array('idpedidos' => $this->getIdPedidos()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function insertCobros(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO cobros (fecha, cobro,obs, idpedidos) 
                                     VALUES(:fecha,:cobro,:obs,:idpedidos);");
        $query->execute(array('fecha' => $this->getFecha(),
            'cobro' => $this->getCobro(),
            'obs' => $this->getObs(),
            'idpedidos' => $this->getIdPedidos()));
        $id = $conexion->lastInsertId();
        $conexion = null;
        return $id;
    }

    public function cancelCobro(){
        $conexion = new Conexion();
        $query = $conexion->prepare("DELETE FROM cobros
                                     WHERE idcobro=:id;");
        $query->execute(array('id' => $this->getIdCobro()));
        $row=$query->rowCount();
        $conexion = null;
        return $row;
    }
    
    
}
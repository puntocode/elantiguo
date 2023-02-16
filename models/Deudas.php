<?php

/**
 * Created by PhpStorm.
 * User: Fede
 * Date: 7/6/2017
 * Time: 5:07 PM
 */
class Deudas{

    private $idDeuda;
    private $fecha;
    private $pagado;
    private $deuda;
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
    public function getIdDeuda()
    {
        return $this->idDeuda;
    }

    /**
     * @param mixed $idDeuda
     */
    public function setIdDeuda($idDeuda)
    {
        $this->idDeuda = $idDeuda;
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
    public function getPagado()
    {
        return $this->pagado;
    }

    /**
     * @param mixed $pagado
     */
    public function setPagado($pagado)
    {
        $this->pagado = $pagado;
    }

    /**
     * @return mixed
     */
    public function getDeuda()
    {
        return $this->deuda;
    }

    /**
     * @param mixed $deuda
     */
    public function setDeuda($deuda)
    {
        $this->deuda = $deuda;
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

    public function selectDeuda(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT fecha,deuda,iddeuda,obs,idpedidos FROM deudas WHERE idpedidos=:idpedidos AND pagado='N';");
        $query->execute(array('idpedidos' => $this->getIdPedidos()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function selectDeudas(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT d.fecha,d.deuda,d.iddeuda,d.obs,c.cliente, d.idpedidos, p.nro_presupuesto
                                    FROM deudas d, pedidos p, cliente c
                                    WHERE d.idpedidos=p.idpedidos AND p.idcliente=c.idcliente AND d.pagado='N'
                                    ORDER BY d.fecha;");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function sumDeudas(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT SUM(deuda) total FROM deudas WHERE idpedidos=:idpedidos AND pagado='N';");
        $query->execute(array('idpedidos' => $this->getIdPedidos()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function insertDeuda(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO deudas (fecha, deuda, pagado, obs, idpedidos) 
                                     VALUES(:fecha,:deuda,:pagado,:obs,:idpedidos);");
        $query->execute(array('fecha' => $this->getFecha(),
            'deuda' => $this->getDeuda(),
            'pagado' => $this->getPagado(),
            'obs' => $this->getObs(),
            'idpedidos' => $this->getIdPedidos()));
        $id = $conexion->lastInsertId();
        $conexion = null;
        return $id;
    }

    public function cancelDebt(){
        $conexion = new Conexion();
        $query = $conexion->prepare("DELETE FROM deudas
                                     WHERE iddeuda=:id;");
        $query->execute(array('id' => $this->getIdDeuda()));
        $row=$query->rowCount();
        $conexion = null;
        return $row;
    }

    public function updatePagado(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE deudas
                                    SET pagado = :pagado
                                    WHERE iddeuda = :id;");
        $query->execute(array('pagado' => $this->getPagado(),
            'id' => $this->getIdDeuda()));
        $modificado= $query->rowCount();
        $conexion = null;
        return $modificado;
    }

    /*---------Calendario Deuda-----------*/
    public function cargarCalendar(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT d.iddeuda, d.idpedidos, d.fecha 'start', d.allday, upper(c.cliente) 'title', CONCAT(UPPER(d.obs), ' - ', CONVERT(FORMAT(d.deuda, 0) using utf8)) 'content'
                                    FROM deudas d, pedidos p, cliente c
                                    WHERE d.idpedidos=p.idpedidos AND p.idcliente=c.idcliente AND d.pagado='N'
                                    ORDER BY d.fecha;");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    //selecciona las deudas por mes y año
    public function seletMesDeuda($mes, $anho){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT DATE_FORMAT(d.fecha, '%d/%m/%Y') fecha,FORMAT(d.deuda,0) deuda, upper(d.obs) obs,c.cliente, p.nro_presupuesto
                                    FROM deudas d, pedidos p, cliente c
                                    WHERE d.idpedidos=p.idpedidos AND p.idcliente=c.idcliente AND d.pagado='N' AND MONTH(d.fecha)=$mes and YEAR(d.fecha)=$anho
                                    ORDER BY d.fecha;");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function cantidadDeuda(){
        $conexion = new Conexion();
        /*$query = $conexion->prepare("SELECT d.iddeuda, d.idpedidos, d.fecha 'start', d.allday, upper(c.cliente) 'title', CONCAT(UPPER(d.obs), ' - ', CONVERT(FORMAT(d.deuda, 0) using utf8)) 'content'
                                    FROM deudas d, pedidos p, cliente c
                                    WHERE d.idpedidos=p.idpedidos AND p.idcliente=c.idcliente AND d.pagado='N'
                                    ORDER BY d.fecha;");*/
        $query = $conexion->prepare("SELECT d.iddeuda, d.idpedidos, DATE_FORMAT(d.fecha, '%d-%m-%Y') fecha, UPPER(c.cliente) cliente, UPPER(d.obs) detalle, CONVERT(FORMAT(d.deuda, 0) using utf8) deuda
                                    FROM deudas d, pedidos p, cliente c
                                    WHERE d.idpedidos=p.idpedidos AND p.idcliente=c.idcliente AND d.pagado='N' AND d.fecha BETWEEN curdate() and curdate() + interval 2 day
                                    ORDER BY d.fecha;");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function deudaDia(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT d.iddeuda, d.idpedidos, DATE_FORMAT(d.fecha, '%d-%m-%Y') fecha, UPPER(c.cliente) cliente, lower(d.obs) detalle, CONVERT(FORMAT(d.deuda, 0) using utf8) deuda
                                    FROM deudas d, pedidos p, cliente c
                                    WHERE d.idpedidos=p.idpedidos AND p.idcliente=c.idcliente AND d.pagado='N' AND d.fecha=:fecha
                                    ORDER BY d.fecha;");
        $query->execute(array('fecha' => $this->getFecha()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

}
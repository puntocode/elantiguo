<?php
/**
 * Created by PhpStorm.
 * User: Fede
 * Date: 11/4/2018
 * Time: 15:28
 */

class Sueldo{

    private $idSueldo;
    private $fecha;
    private $ausencia;
    private $concepto;
    private $adelanto;
    private $descuento;
    private $total;
    private $pagado;
    private $idEmpleado;

    /**
     * @return mixed
     */
    public function getIdSueldo()
    {
        return $this->idSueldo;
    }

    /**
     * @param mixed $idSueldo
     */
    public function setIdSueldo($idSueldo)
    {
        $this->idSueldo = $idSueldo;
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
    public function getAusencia()
    {
        return $this->ausencia;
    }

    /**
     * @param mixed $ausencia
     */
    public function setAusencia($ausencia)
    {
        $this->ausencia = $ausencia;
    }

    /**
     * @return mixed
     */
    public function getConcepto()
    {
        return $this->concepto;
    }

    /**
     * @param mixed $concepto
     */
    public function setConcepto($concepto)
    {
        $this->concepto = $concepto;
    }

    /**
     * @return mixed
     */
    public function getAdelanto()
    {
        return $this->adelanto;
    }

    /**
     * @param mixed $adelanto
     */
    public function setAdelanto($adelanto)
    {
        $this->adelanto = $adelanto;
    }

    /**
     * @return mixed
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * @param mixed $descuento
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;
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
    public function getIdEmpleado()
    {
        return $this->idEmpleado;
    }

    /**
     * @param mixed $idEmpleado
     */
    public function setIdEmpleado($idEmpleado)
    {
        $this->idEmpleado = $idEmpleado;
    }

    public function verMes($inicio, $fin){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT s.idsueldo, s.fecha, s.concepto, s.total, e.nombre
                                    FROM sueldo s, empleado e
                                    WHERE s.idempleado=e.idempleado AND s.fecha BETWEEN :desde AND :hasta
                                    ORDER BY fecha;");
        $query->execute(array('desde' => $inicio,
                              'hasta' => $fin));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    /**
     * @total de sueldo por filtro de fecha
     */
    public function sumSueldo($inicio, $fin){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT sum(total) total
                                    FROM sueldo
                                    WHERE fecha BETWEEN :desde AND :hasta;");
        $query->execute(array('desde' => $inicio,
                              'hasta' => $fin));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }


    /**
     * @id del ultimo sueldo insertado
     */
    public function insertSalary(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO sueldo(fecha, concepto, total, idempleado)
                                     VALUES(:fecha,:concepto,:total,:idempleado);");
        $query->execute(array('fecha' => $this->getFecha(),
            'concepto' => $this->getConcepto(),
            'total' => $this->getTotal(),
            'idempleado' => $this->getIdEmpleado()));
        $id = $conexion->lastInsertId();
        $conexion = null;
        return $id;
    }

    /**
     * @rowcount si el sueldo ha sido eliminado
     */
    public function deleteSalary(){
        $conexion = new Conexion();
        $query = $conexion->prepare("DELETE FROM sueldo WHERE idsueldo = :id;");
        $query->execute(array('id' => $this->getIdSueldo()));
        $conexion = null;
        return $query->rowCount();
    }

}
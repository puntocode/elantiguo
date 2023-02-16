<?php

class Inventario{

    private $idinventario;
    private $idstock;
    private $cantidad;
    private $fecha;
    private $motivo;
    private $idempleado;
    private $iduser;
    private $movimiento;
    private $pendiente;

    /**
     * @return mixed
     */
    public function getPendiente()
    {
        return $this->pendiente;
    }

    /**
     * @param mixed $pendiente
     */
    public function setPendiente($pendiente)
    {
        $this->pendiente = $pendiente;
    }




    /**
     * @return mixed
     */
    public function getIdinventario()
    {
        return $this->idinventario;
    }

    /**
     * @param mixed $idinventario
     */
    public function setIdinventario($idinventario)
    {
        $this->idinventario = $idinventario;
    }

    /**
     * @return mixed
     */
    public function getIdstock()
    {
        return $this->idstock;
    }

    /**
     * @param mixed $idstock
     */
    public function setIdstock($idstock)
    {
        $this->idstock = $idstock;
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
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * @param mixed $motivo
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
    }

    /**
     * @return mixed
     */
    public function getIdempleado()
    {
        return $this->idempleado;
    }

    /**
     * @param mixed $idempleado
     */
    public function setIdempleado($idempleado)
    {
        $this->idempleado = $idempleado;
    }

    /**
     * @return mixed
     */
    public function getIduser()
    {
        return $this->iduser;
    }

    /**
     * @param mixed $iduser
     */
    public function setIduser($iduser)
    {
        $this->iduser = $iduser;
    }

    /**
     * @return mixed
     */
    public function getMovimiento()
    {
        return $this->movimiento;
    }

    /**
     * @param mixed $movimiento
     */
    public function setMovimiento($movimiento)
    {
        $this->movimiento = $movimiento;
    }

    public function inventarioMes(){
        $mes=date("m");
        $anho=date("Y");
        $sentencia = "SELECT i.movimiento, i.idinventario, i.fecha, s.producto, s.unidad, i.cantidad, i.motivo, e.nombre
                      FROM inventario i, stock s, empleado e
                      WHERE i.idstock = s.idstock and i.idempleado = e.idempleado AND MONTH(i.fecha)=$mes and YEAR(i.fecha)=$anho
                      ORDER BY i.idinventario DESC;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }


    public function registrarInventario(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO inventario(idstock,cantidad,fecha,motivo,idempleado,iduser,movimiento,pendiente)
                                    VALUES(:idstock, :cantidad, :fecha, :motivo, :idempleado, :iduser, :movimiento, :pendiente);");
        $query->execute(array('idstock' => $this->getIdstock(),
            'cantidad' => $this->getCantidad(),
            'fecha' => $this->getFecha(),
            'motivo' => $this->getMotivo(),
            'idempleado' => $this->getIdempleado(),
            'iduser' => $this->getIduser(),
            'movimiento' =>$this->getMovimiento(),
            'pendiente' => $this->getPendiente()));
        $conexion = null;
    }

    public function updateStock(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE stock SET cantidad = :cantidad
                                    WHERE idstock = :idstock;");
        $query->execute(array('cantidad' => $this->getCantidad(), 'idstock' => $this->getIdstock()));
        $conexion = null;
    }

    public function updateCantInventario(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE inventario SET cantidad = :cantidad
                                    WHERE idinventario = :id;");
        $query->execute(array('cantidad' => $this->getCantidad(), 'id' => $this->getIdinventario()));
        $conexion = null;
    }

    public function selectEntSal(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT i.idinventario, i.fecha, s.producto, i.cantidad, e.nombre, i.motivo
                                      FROM inventario i, stock s, empleado e
                                      WHERE i.idstock = s.idstock and i.idempleado = e.idempleado AND movimiento = :movimiento
                                      ORDER BY idinventario DESC LIMIT 1");
        $query->execute(array('movimiento' => $this->getMovimiento() ));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function selectInventario(){
        $conexion = new Conexion();
        $query = $conexion->prepare("select * from inventario where idinventario= :id");
        $query->execute(array('id' => $this->getIdinventario() ));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function updateInventario(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE inventario
                                    SET fecha = :fecha, idstock = :idstock, cantidad = :cantidad, motivo = :motivo,
                                    idempleado = :idempleado, iduser = :iduser
                                    WHERE idinventario = :id;");
        $query->execute(array('fecha' => $this->getFecha(),
            'idstock' => $this->getIdstock(),
            'cantidad' => $this->getCantidad(),
            'motivo' => $this->getMotivo(),
            'idempleado' => $this->getIdempleado(),
            'iduser' => $this->getIduser(),
            'id' => $this->getIdinventario()));
        return $query->rowCount();
        $conexion = null;
    }

    public function deleteInventario(){
        $conexion = new Conexion();
        $query = $conexion->prepare("DELETE FROM inventario WHERE idinventario = :id;");
        $query->execute(array('id' => $this->getIdinventario()));
        return $query->rowCount();
        $conexion = null;
    }

    public function pendientes(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT i.idinventario, i.fecha, s.producto, i.cantidad, s.unidad, i.motivo, e.nombre
                                    FROM inventario i, stock s, empleado e
                                    WHERE i.idstock = s.idstock and i.idempleado = e.idempleado
                                    and i.pendiente = 'P';");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function updatePendiente(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE inventario SET pendiente = 'N'
                                    WHERE idinventario = :id;");
        $query->execute(array('id' => $this->getIdinventario()));
        $conexion = null;
    }

    public function selectMaxId(){
        $sentencia = "SELECT MAX(idinventario) codigo FROM inventario;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }


}
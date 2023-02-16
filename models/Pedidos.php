<?php

/**
 * Created by PhpStorm.
 * User: Fede
 * Date: 18/5/2017
 * Time: 1:01 PM
 */
class Pedidos{
    private $idPedidos;
    private $nroPresupuesto;
    private $fecha;
    private $idCliente;
    private $entregaEstimada;
    private $entrega;
    private $senha;
    private $pagado;
    private $facturado;
    private $abonado;
    private $obs;
    private $factura;

    /**
     * @return mixed
     */
    public function getFactura()
    {
        return $this->factura;
    }

    /**
     * @param mixed $factura
     */
    public function setFactura($factura)
    {
        $this->factura = $factura;
    }


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
    public function getAbonado()
    {
        return $this->abonado;
    }

    /**
     * @param mixed $abonado
     */
    public function setAbonado($abonado)
    {
        $this->abonado = $abonado;
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

    /**
     * @return mixed
     */
    public function getNroPresupuesto()
    {
        return $this->nroPresupuesto;
    }

    /**
     * @param mixed $nroPresupuesto
     */
    public function setNroPresupuesto($nroPresupuesto)
    {
        $this->nroPresupuesto = $nroPresupuesto;
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
    public function getIdCliente()
    {
        return $this->idCliente;
    }

    /**
     * @param mixed $idCliente
     */
    public function setIdCliente($idCliente)
    {
        $this->idCliente = $idCliente;
    }

    /**
     * @return mixed
     */
    public function getEntregaEstimada()
    {
        return $this->entregaEstimada;
    }

    /**
     * @param mixed $entregaEstimada
     */
    public function setEntregaEstimada($entregaEstimada)
    {
        $this->entregaEstimada = $entregaEstimada;
    }

    /**
     * @return mixed
     */
    public function getEntrega()
    {
        return $this->entrega;
    }

    /**
     * @param mixed $entrega
     */
    public function setEntrega($entrega)
    {
        $this->entrega = $entrega;
    }

    /**
     * @return mixed
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * @param mixed $senha
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;
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
    public function getFacturado()
    {
        return $this->facturado;
    }

    /**
     * @param mixed $facturado
     */
    public function setFacturado($facturado)
    {
        $this->facturado = $facturado;
    }

    public function vertodos(){
        $conexion = new Conexion();

        $query = $conexion->prepare("SELECT p.idpedidos, p.fecha, c.cliente, c.contacto, p.entrega_estimada, p.entrega, p.pagado, p.facturado, p.senha, 
                                    p.nro_presupuesto, SUM(d.cantidad*d.costo) total, p.abonado
                                    FROM detalle_pedido d, pedidos p, cliente c
                                    WHERE p.idcliente=c.idcliente AND p.idpedidos=d.idpedidos AND p.pagado='N'
                                    GROUP BY idpedidos
                                    ORDER BY p.nro_presupuesto;");
        $query->execute(array('pagado' => $this->getPagado()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function selectPedido(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT p.idpedidos, p.fecha, p.idcliente, c.cliente, c.contacto, p.entrega_estimada, p.entrega, p.pagado, p.facturado, p.senha, p.nro_presupuesto, p.obs, p.facturado
                                     FROM pedidos p, cliente c
                                     WHERE p.idcliente=c.idcliente AND p.idpedidos=:id;");
        $query->execute(array('id' => $this->getIdPedidos()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function selectPedidosByPagado(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT p.idpedidos, p.fecha, c.idcliente, c.cliente, c.contacto, p.entrega_estimada, p.entrega, p.pagado, p.facturado, p.nro_presupuesto, sum(d.cantidad*d.costo) total
                                    FROM pedidos p, detalle_pedido d, cliente c
                                    WHERE p.idpedidos=d.idpedidos AND p.idcliente=c.idcliente AND p.pagado=:pagado
                                    GROUP BY idpedidos;");
        $query->execute(array('pagado' => $this->getPagado()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function insertPedidos(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO pedidos (fecha,idcliente,pagado,facturado,nro_presupuesto, senha) 
                                     VALUES(:fecha,:idcliente,:pagado,:facturado,:nro_presupuesto, :senha);");
        $query->execute(array('fecha' => $this->getFecha(),
                            'idcliente' => $this->getIdCliente(),
                            'pagado' => $this->getPagado(),
                            'facturado' => $this->getFacturado(),
                            'nro_presupuesto' => $this->getNroPresupuesto(),
                            'senha' => $this->getSenha()
        ));
        $id = $conexion->lastInsertId();
        $conexion = null;
        return $id;
    }

    public function cancelarPedido(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE pedidos
                                    SET pagado = :pagado
                                    WHERE idpedidos = :id;");
        $query->execute(array('pagado' => $this->getPagado(),
            'id' => $this->getIdPedidos()));
        $modificado= $query->rowCount();
        $conexion = null;
        return $modificado;
    }

    public function updateEntrega(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE pedidos
                                    SET entrega = :entrega
                                    WHERE idpedidos = :id;");
        $query->execute(array('entrega' => $this->getEntrega(),
            'id' => $this->getIdPedidos()));
        $modificado= $query->rowCount();
        $conexion = null;
        return $modificado;
    }

    public function updateSenha(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE pedidos
                                    SET senha = :senha
                                    WHERE idpedidos = :id;");
        $query->execute(array('senha' => $this->getSenha(),
            'id' => $this->getIdPedidos()));
        $modificado= $query->rowCount();
        $conexion = null;
        return $modificado;
    }

    public function updatePedido(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE pedidos
                                    SET fecha = :fecha, idcliente = :idcliente, nro_presupuesto = :nro, factura =:factura
                                    WHERE idpedidos = :id;");
        $query->execute(array('fecha' => $this->getFecha(),
            'idcliente' => $this->getIdCliente(),
            'nro' => $this->getNroPresupuesto(),
            'factura' => $this->getFactura(),
            'id' => $this->getIdPedidos()));
        $modificado= $query->rowCount();
        $conexion = null;
        return $modificado;
    }
    
    public function updateAbonado(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE pedidos
                                    SET abonado = :abonado
                                    WHERE idpedidos = :id;");
        $query->execute(array('abonado' => $this->getAbonado(),
            'id' => $this->getIdPedidos()));
        $modificado= $query->rowCount();
        $conexion = null;
        return $modificado;
    }

    public function updateObs(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE pedidos
                                    SET obs = :obs
                                    WHERE idpedidos = :id;");
        $query->execute(array('obs' => $this->getObs(),
            'id' => $this->getIdPedidos()));
        $modificado= $query->rowCount();
        $conexion = null;
        return $modificado;
    }
    
}

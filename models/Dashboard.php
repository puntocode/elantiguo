<?php

class Dashboard{

    private $mes;
    private $anho;
    private $movimiento;
    private $limit;
    private $tipoIngress;
    private $estado;
    private $idProveedor;
    private $idCliente;

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
    public function getIdProveedor()
    {
        return $this->idProveedor;
    }

    /**
     * @param mixed $idProveedor
     */
    public function setIdProveedor($idProveedor)
    {
        $this->idProveedor = $idProveedor;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return mixed
     */
    public function getTipoIngress()
    {
        return $this->tipoIngress;
    }

    /**
     * @param mixed $tipoIngress
     */
    public function setTipoIngress($tipoIngress)
    {
        $this->tipoIngress = $tipoIngress;
    }

    /**
     * @return mixed
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @param mixed $mes
     */
    public function setMes($mes)
    {
        $this->mes = $mes;
    }

    /**
     * @return mixed
     */
    public function getAnho()
    {
        return $this->anho;
    }

    /**
     * @param mixed $anho
     */
    public function setAnho($anho)
    {
        $this->anho = $anho;
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

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }


    public function cantidadInventario(){
        $conexion = new Conexion();
        $limit = $this->getLimit();
        $query = $conexion->prepare("SELECT s.producto,sum(i.cantidad) cantidad, sum(s.costo) costo 
                                    FROM inventario i, stock s
                                    WHERE i.idstock = s.idstock and MONTH(i.fecha)=:mes and YEAR(i.fecha)=:anho AND i.movimiento=:movimiento
                                    GROUP BY producto  ORDER BY costo DESC 
                                    LIMIT $limit;");
        $query->execute(array('mes' => $this->getMes(),
                            'anho' => $this->getAnho(),
                            'movimiento' => $this->getMovimiento()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function sumEgreso(){
        //$sentencia = "SELECT SUM(total) total FROM pasivo WHERE MONTH(fecha)=$mes and YEAR(fecha)=$anho;";
        $sentencia = "SELECT SUM(d.total) egreso
                      FROM pasivo p, detalle_pasivo d
                      WHERE p.idpasivo=d.idpasivo
                      AND MONTH(p.fecha)=:mes and YEAR(fecha)=:anho
                      AND p.estado='C';";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('mes' => $this->getMes(),
            'anho' => $this->getAnho()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function returnGastosExentos(){
        //$sentencia = "SELECT SUM(total) total FROM pasivo WHERE MONTH(fecha)=$mes and YEAR(fecha)=$anho;";
        $sentencia = "SELECT SUM(d.total) exento
                      FROM pasivo p, detalle_pasivo d
                      WHERE p.idpasivo=d.idpasivo
                      AND MONTH(p.fecha)=:mes and YEAR(fecha)=:anho
                      AND p.estado='C' AND d.impuesto=0;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('mes' => $this->getMes(),
            'anho' => $this->getAnho()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function returnGastosIva(){
        //$sentencia = "SELECT SUM(total) total FROM pasivo WHERE MONTH(fecha)=$mes and YEAR(fecha)=$anho;";
        $sentencia = "SELECT SUM(d.total) impuesto
                      FROM pasivo p, detalle_pasivo d
                      WHERE p.idpasivo=d.idpasivo
                      AND MONTH(p.fecha)=:mes and YEAR(fecha)=:anho
                      AND p.estado='C' AND d.impuesto>0;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('mes' => $this->getMes(),
            'anho' => $this->getAnho()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }


    public function sumIngress(){
        //$sentencia = "SELECT SUM(total) total FROM pasivo WHERE MONTH(fecha)=$mes and YEAR(fecha)=$anho;";
        $sentencia = "SELECT sum(d.total) total
                      FROM activo a, detalle_activo d
                      WHERE a.idactivo=d.idactivo AND estado=:estado AND a.tipo_ingress=:tipo
                      AND MONTH(fecha)=:mes and YEAR(fecha)=:anho;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('estado' => $this->getEstado(),
            'tipo' => $this->getTipoIngress(),
            'mes' => $this->getMes(),
            'anho' => $this->getAnho()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    
    /*---------Proveedores-----------------*/
    public function chartProveedores(){
        $limit = $this->getLimit();
        $sentencia = "SELECT pr.idproveedor, upper(pr.proveedor) proveedor, SUM(d.total) egreso
                     FROM pasivo p, detalle_pasivo d, proveedor pr
                     WHERE p.idpasivo=d.idpasivo AND p.idproveedor=pr.idproveedor
                     AND MONTH(p.fecha)=:mes and YEAR(fecha)=:anho AND p.estado='C'
                     GROUP BY idproveedor ORDER BY egreso DESC LIMIT $limit;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('mes' => $this->getMes(),
            'anho' => $this->getAnho()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function detailProvider(){
        $sentencia = "SELECT p.fecha, p.nro_factura, d.producto, d.costo, d.cantidad
                    FROM detalle_pasivo d, pasivo p
                    WHERE d.idpasivo=p.idpasivo AND p.idproveedor=:idproveedor AND MONTH(p.fecha)=:mes and YEAR(fecha)=:anho
                    ORDER BY p.fecha DESC";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array(
            'idproveedor' => $this->getIdProveedor(),
            'mes' => $this->getMes(),
            'anho' => $this->getAnho()
            ));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function sumProvider(){
        $sentencia = "SELECT  pr.proveedor, sum(d.total) total
                      FROM detalle_pasivo d, pasivo p, proveedor pr
                      WHERE d.idpasivo=p.idpasivo AND p.idproveedor=pr.idproveedor AND p.idproveedor=:id
                      AND MONTH(p.fecha)=:mes and YEAR(fecha)=:anho";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array(
            'id' => $this->getIdProveedor(),
            'mes' => $this->getMes(),
            'anho' => $this->getAnho()
        ));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }
    /*---------Proveedores-----------------*/

    /*---------------------------------------------------Clientes----------------------------------------------------------------------*/
    public function chartCliente(){
        $limit = $this->getLimit();
        $sentencia = "SELECT c.idcliente, upper(c.cliente) cliente, SUM(d.total) ingreso
                    FROM activo a, detalle_activo d, cliente c
                    WHERE a.idactivo=d.idactivo AND a.idcliente=c.idcliente AND MONTH(a.fecha)=:mes and YEAR(a.fecha)=:anho AND a.tipo_ingress='M'
                    GROUP BY idcliente
                    ORDER BY ingreso DESC LIMIT $limit;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('mes' => $this->getMes(),
            'anho' => $this->getAnho()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function detailCustomer(){
        $sentencia = "SELECT a.fecha, a.nro_factura, d.producto, d.costo, d.cantidad
                    FROM detalle_activo d, activo a
                    WHERE d.idactivo=a.idactivo AND a.idcliente=:idcliente AND MONTH(a.fecha)=:mes and YEAR(a.fecha)=:anho
                    ORDER BY a.fecha";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array(
            'idcliente' => $this->getIdCliente(),
            'mes' => $this->getMes(),
            'anho' => $this->getAnho()
        ));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function sumCustomer(){
        $sentencia = "SELECT  c.cliente, sum(d.total) total
                      FROM detalle_activo d, activo a, cliente c
                      WHERE d.idactivo=a.idactivo AND a.idcliente=c.idcliente AND a.idcliente=:id
                      AND MONTH(a.fecha)=:mes and YEAR(a.fecha)=:anho";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array(
            'id' => $this->getIdCliente(),
            'mes' => $this->getMes(),
            'anho' => $this->getAnho()
        ));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }
    /*---------------------------------------------------Clientes----------------------------------------------------------------------*/



    public function chartProductos(){
        $limit = $this->getLimit();
        $sentencia = "SELECT upper(d.producto) producto, SUM(d.total) ingreso, SUM(d.cantidad) cantidad, d.costo
                        FROM activo a, detalle_activo d
                        WHERE a.idactivo=d.idactivo AND MONTH(a.fecha)=:mes and YEAR(a.fecha)=:anho AND a.tipo_ingress='M' AND a.estado='F'
                        GROUP BY producto
                        ORDER BY ingreso DESC LIMIT $limit;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('mes' => $this->getMes(),
            'anho' => $this->getAnho()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function sumIngressAnual(){
        $sentencia = "SELECT date_format(a.fecha, '%m') mes, SUM(d.total)  total
                        FROM activo a, detalle_activo d
                        WHERE a.idactivo=d.idactivo AND a.tipo_ingress='M' AND YEAR(a.fecha) = :anho
                        GROUP BY mes;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('anho' => $this->getAnho()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function sumEgressAnual(){
        $sentencia = "SELECT date_format(p.fecha, '%m') mes, SUM(d.total)  total
                    FROM pasivo p, detalle_pasivo d
                    WHERE p.idpasivo=d.idpasivo AND p.estado='C' AND YEAR(p.fecha) = :anho
                    GROUP BY mes;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('anho' => $this->getAnho()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

}
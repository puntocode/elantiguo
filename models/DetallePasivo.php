<?php
class DetallePasivo{

    private $idDetallePasivo;
    private $cantidad;
    private $costo;
    private $idPasivo;
    private $total;
    private $impuesto;
    private $producto;
    private $idInventario;

    /**
     * @return mixed
     */
    public function getIdInventario()
    {
        return $this->idInventario;
    }

    /**
     * @param mixed $idInventario
     */
    public function setIdInventario($idInventario)
    {
        $this->idInventario = $idInventario;
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
    public function getImpuesto()
    {
        return $this->impuesto;
    }

    /**
     * @param mixed $impuesto
     */
    public function setImpuesto($impuesto)
    {
        $this->impuesto = $impuesto;
    }


    /**
     * @return mixed
     */
    public function getIdDetallePasivo()
    {
        return $this->idDetallePasivo;
    }

    /**
     * @param mixed $idDetallePasivo
     */
    public function setIdDetallePasivo($idDetallePasivo)
    {
        $this->idDetallePasivo = $idDetallePasivo;
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
    public function getIdPasivo()
    {
        return $this->idPasivo;
    }

    /**
     * @param mixed $idPasivo
     */
    public function setIdPasivo($idPasivo)
    {
        $this->idPasivo = $idPasivo;
    }


    public function returnFacture(){
        $sentencia = "SELECT  iddetalle_pasivo, cantidad, costo, producto, total, idpasivo, idinventario
                      FROM detalle_pasivo
                      WHERE idpasivo = :id;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('id' => $this->getIdPasivo()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }


    public function insertDetallePasivo(){
        try{
            $conexion = new Conexion();
            $query = $conexion->prepare("INSERT INTO detalle_pasivo (idpasivo,cantidad,costo,total,impuesto,producto,idinventario)
                                         VALUES(:idpasivo,:cantidad,:costo,:total,:impuesto,:producto,:idinventario);");
            $query->execute(array('idpasivo' => $this->getIdPasivo(),
                'cantidad' => $this->getCantidad(),
                'costo' => $this->getCosto(),
                'total' => $this->getTotal(),
                'impuesto'=>$this->getImpuesto(),
                'producto'=>$this->getProducto(),
                'idinventario'=>$this->getIdInventario()));
            return $query->rowCount();
            $conexion = null;
        }catch(PDOException $e){
            echo $e->getMessage(); exit;
        }
        
    }

    public function returnMaxId(){
        $conexion = new Conexion();
        $query = $conexion->prepare("select MAX(iddetalle_pasivo) codigo from detalle_pasivo;");
        $query->execute(array('id' => $this->getIdPasivo()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function returnSumTotalImp(){
        $conexion = new Conexion();
        $query = $conexion->prepare("select sum(total) total, sum(impuesto) impuesto from detalle_pasivo where idpasivo=:id;");
        $query->execute(array('id' => $this->getIdPasivo()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }


    public function deleteDetallePasivo(){
        $conexion = new Conexion();
        $query = $conexion->prepare("DELETE FROM detalle_pasivo
                                    WHERE iddetalle_pasivo = :id;");
        $query->execute(array('id' => $this->getIdDetallePasivo()));
        return $query->rowCount();
        $conexion = null;
    }


    public function returnCabecera(){
        $sentencia = "SELECT p.fecha, pr.proveedor, p.nro_factura, p.idpasivo, p.descuento FROM pasivo p, proveedor pr
                      WHERE p.idproveedor=pr.idproveedor and idpasivo = :id;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('id' => $this->getIdPasivo()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }


    public function egressStock(){
        $sentencia = "SELECT p.fecha, p.nro_factura, pr.proveedor, d.producto, d.costo
                    FROM detalle_pasivo d, pasivo p, proveedor pr
                    WHERE d.idpasivo=p.idpasivo AND p.idproveedor=pr.idproveedor
                    ORDER BY p.fecha DESC
                    LIMIT 100;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function searchEgressStock(){
        $conexion = new Conexion();
        $likeVar = "%" . $this->getProducto() . "%";
        $query = $conexion->prepare("SELECT p.fecha, p.nro_factura, pr.proveedor, d.producto, d.costo
                    FROM detalle_pasivo d, pasivo p, proveedor pr
                    WHERE d.idpasivo=p.idpasivo AND p.idproveedor=pr.idproveedor
                    AND producto LIKE :producto 
                    ORDER BY producto;");
        $query->execute(array('producto' => $likeVar ));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;

    }





}
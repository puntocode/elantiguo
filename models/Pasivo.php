<?php

/**
 * Created by PhpStorm.
 * User: FedeXavier
 * Date: 14/03/2017
 * Time: 03:43 PM
 */
class Pasivo{

    private $idpasivo;
    private $fecha;
    private $nroFactura;
    private $idproveedor;
    private $estado;
    private $descuento;

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
    public function getIdpasivo()
    {
        return $this->idpasivo;
    }

    /**
     * @param mixed $idpasivo
     */
    public function setIdpasivo($idpasivo)
    {
        $this->idpasivo = $idpasivo;
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
    public function getNroFactura()
    {
        return $this->nroFactura;
    }

    /**
     * @param mixed $nroFactura
     */
    public function setNroFactura($nroFactura)
    {
        $this->nroFactura = $nroFactura;
    }

    /**
     * @return mixed
     */
    public function getIdproveedor()
    {
        return $this->idproveedor;
    }

    /**
     * @param mixed $idproveedor
     */
    public function setIdproveedor($idproveedor)
    {
        $this->idproveedor = $idproveedor;
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


    public function egrressMonth($mes, $anho){
        $sentencia = "SELECT  p.idpasivo, p.fecha, v.proveedor, p.nro_factura, SUM(d.total) total, SUM(d.impuesto) impuesto, p.estado, p.descuento
                      FROM pasivo p, detalle_pasivo d, proveedor v
                      WHERE p.idpasivo = d.idpasivo and p.idproveedor=v.idproveedor AND MONTH(p.fecha)=$mes and YEAR(p.fecha)=$anho
                      GROUP BY idpasivo
                      ORDER BY p.fecha;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function sumTotal($mes, $anho){
        //$sentencia = "SELECT SUM(total) total FROM pasivo WHERE MONTH(fecha)=$mes and YEAR(fecha)=$anho;";
        $sentencia = "SELECT SUM(d.total) total
                      FROM pasivo p, detalle_pasivo d
                      WHERE p.idpasivo=d.idpasivo
                      AND MONTH(p.fecha)=$mes and YEAR(fecha)=$anho
                      AND p.estado='C';";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function insertEgress(){
        try{
            $conexion = new Conexion();
        
            $query = $conexion->prepare("INSERT INTO pasivo(fecha,idproveedor,nro_factura,estado)
                VALUES(:fecha, :idproveedor, :nro_factura, :estado);");
            
            $query->execute(array(
                'estado'      => $this->getEstado(),
                'fecha'       => $this->getFecha(),
                'idproveedor' => $this->getIdproveedor(),
                'nro_factura' => $this->getNroFactura()
            ));
            
            return $conexion->lastInsertId();
        }catch (PDOException $e) {
            echo 'Exception Connection: ' . $e->getMessage();
            exit;
        }
    }

    public function selectMaxId(){
        $sentencia = "SELECT MAX(idpasivo) codigo FROM pasivo;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    public function insertDescuento(){
        $sentencia = "UPDATE pasivo SET descuento = :descuento WHERE idpasivo=:id;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('descuento' => $this->getDescuento(),
            'id'=> $this->getIdpasivo()));
        $conexion = null;
        return $query->rowCount();
    }

    public function nullFacture(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE pasivo SET estado = 'A'
                                     WHERE idpasivo = :id;");
        $query->execute(array('id' => $this->getIdpasivo()));
        return $query->rowCount();
        $conexion = null;
    }

    public function updateFacture(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE pasivo SET idproveedor=:idproveedor, fecha=:fecha, nro_factura=:nrofactura
                                     WHERE idpasivo = :id;");
        $query->execute(array('idproveedor' => $this->getIdproveedor(),
            'fecha' => $this->getFecha(),
            'nrofactura' => $this->getNroFactura(),
            'id' => $this->getIdpasivo()));
        return $query->rowCount();
        $conexion = null;
    }

    /**
     * @return array resultado busqueda por proveedor
     */
    public function searchProvider(){
        $provider= "%".$this->getEstado()."%";
        $sentencia = "SELECT p.fecha, p.nro_factura, p.estado, r.proveedor, d.cantidad, d.costo, d.total, d.producto
                    FROM pasivo p, proveedor r, detalle_pasivo d
                    WHERE p.idpasivo=d.idpasivo AND p.idproveedor=r.idproveedor AND p.estado='C'
                    AND proveedor like :proveedor;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('proveedor' => $provider));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    /**
     * @return array resultado busqueda por producto
     */
    public function searchProduct(){
        $product= "%".$this->getEstado()."%";
        $sentencia = "SELECT p.fecha, p.nro_factura, p.estado, r.proveedor, d.cantidad, d.costo, d.total, d.producto
                        FROM pasivo p, proveedor r, detalle_pasivo d
                        WHERE p.idpasivo=d.idpasivo AND p.idproveedor=r.idproveedor AND p.estado='C'
                        AND producto like :producto;";
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute(array('producto' => $product));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }



}
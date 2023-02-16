<?php

class Abm{

    private $idabm;
    private $tabla;
    private $campo;
    private $iduser;
    private $motivo;
    private $fecha;

    /**
     * @return mixed
     */
    public function getIdabm()
    {
        return $this->idabm;
    }

    /**
     * @param mixed $idabm
     */
    public function setIdabm($idabm)
    {
        $this->idabm = $idabm;
    }

    /**
     * @return mixed
     */
    public function getTabla()
    {
        return $this->tabla;
    }

    /**
     * @param mixed $tabla
     */
    public function setTabla($tabla)
    {
        $this->tabla = $tabla;
    }

    /**
     * @return mixed
     */
    public function getCampo()
    {
        return $this->campo;
    }

    /**
     * @param mixed $campo
     */
    public function setCampo($campo)
    {
        $this->campo = $campo;
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

    public function sentencias($sentencia){
        $conexion = new Conexion();
        $query = $conexion->prepare($sentencia);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function insertarAbm(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO abm(tabla,campo,motivo,iduser,fecha)
                                    VALUES(:tabla, :campo, :motivo, :iduser, now());");
        $query->execute(array('tabla' => $this->getTabla(),
            'campo' => $this->getCampo(),
            'motivo' => $this->getMotivo(),
            'iduser' =>$this->getIduser()));
        $conexion = null;
    }


}
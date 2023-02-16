<?php

/**
 * Created by PhpStorm.
 * User: Fede
 * Date: 3/5/2017
 * Time: 10:56 AM
 */
class Presupuesto{
    private $idPresupuesto;
    private $numero;
    private $cliente;
    private $fecha;
    private $estado;
    private $descripcion;
    private $sustantivo;

    /**
     * Get the value of estado
     */ 
    public function getEstado(){
        return $this->estado;
    }

    /**
     * Set the value of estado
     *
     * @return  self
     */ 
    public function setEstado($estado){
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get the value of fecha
     */ 
    public function getFecha(){
        return $this->fecha;
    }

    /**
     * Set the value of fecha
     *
     * @return  self
     */ 
    public function setFecha($fecha){
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get the value of cliente
     */ 
    public function getCliente(){
        return $this->cliente;
    }

    /**
     * Set the value of cliente
     *
     * @return  self
     */ 
    public function setCliente($cliente){
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get the value of numero
     */ 
    public function getNumero(){
        return $this->numero;
    }

    /**
     * Set the value of numero
     *
     * @return  self
     */ 
    public function setNumero($numero){
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get the value of descripcion
     */ 
    public function getDescripcion(){
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     *
     * @return  self
     */ 
    public function setDescripcion($descripcion){
        $this->descripcion = $descripcion;

        return $this;
    }

      /**
     * Set the value of descripcion
     *
     * @return  self
     */ 
    public function setIdPresupuesto($idPresupuesto){
        $this->idPresupuesto = $idPresupuesto;

        return $this;
    }

    /**
     * Get the value of idPresupuesto
     */ 
    public function getIdPresupuesto(){
        return $this->idPresupuesto;
    }

    /**
     * Get the value of sustantivo
     */ 
    public function getSustantivo()
    {
        return $this->sustantivo;
    }

    /**
     * Set the value of sustantivo
     *
     * @return  self
     */ 
    public function setSustantivo($sustantivo)
    {
        $this->sustantivo = $sustantivo;

        return $this;
    }

    //--------------------------------------------------------End Getter and Setter---------------------------------------------------------------------------

    public function selectMaxId(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT MAX(idpresupuesto) id FROM presupuesto;");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    /**
     * get the result of the presupuesto table by estado
     */ 
    public function selectEstado(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT idpresupuesto,cliente,fecha,estado,numero,descripcion
                                    FROM presupuesto
                                    WHERE estado= :state;");
        $query->execute(array('state' => $this->getEstado()));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    /**
     * get the result of the presupuesto table by id 
     */ 
    public function selectById(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT idpresupuesto,cliente,fecha,estado,numero,descripcion,sustantivo
                                    FROM presupuesto
                                    WHERE idpresupuesto= :id;");
        $query->execute(array('id' => $this->getIdPresupuesto()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
        return $result;
    }

    /**
     * Insert New Budget 
     */ 
    public function insertBudget(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO presupuesto(cliente,fecha,estado,numero,descripcion,sustantivo)
                                    VALUES(:customer,:date,:state,:number,:description,:noun);");
        $query->execute(array(
            'customer' => $this->getCliente(),
            'date' => $this->getFecha(),
            'state' => $this->getEstado(),
            'number' => $this->getNumero(),
            'description' => $this->getDescripcion(),
            'noun' => $this->getSustantivo()
        ));
        return $query->rowCount();
        $conexion = null;
    }

    /**
     * update estado from the table presupuesto by id_presupuesto
     */ 
    public function updateEstado(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE presupuesto
                                    SET estado = :estado
                                    WHERE idpresupuesto = :id;");
        $query->execute(array(
            'estado' => $this->getEstado(),
            'id' => $this->getIdPresupuesto()
        ));
        $conexion = null;
        return $query->rowCount();
    }

    /**
     * update cliente,fecha from the table presupuesto by id_presupuesto
     */ 
    public function updateBudget(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE presupuesto
                                    SET fecha = :fecha, cliente = :cliente, descripcion = :descripcion, sustantivo = :sustantivo
                                    WHERE idpresupuesto = :id;");
        $query->execute(array(
            'fecha' => $this->getFecha(),
            'cliente' => $this->getCliente(),
            'descripcion' => $this->getDescripcion(),
            'sustantivo' => $this->getSustantivo(),
            'id' => $this->getIdPresupuesto()
        ));
        $conexion = null;
        return $query->rowCount();
    }

    
}
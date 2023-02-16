<?php

/**
 * Created by PhpStorm.
 * User: FedeXavier
 * Date: 22/11/2016
 * Time: 05:46 PM
 */
class Empleado{

    private $idempleado;
    private $nombre;
    private $ci;
    private $telefono;
    private $email;
    private $ciudad;
    private $sueldo;
    private $nacimiento;

    /**
     * @return mixed
     */
    public function getNacimiento()
    {
        return $this->nacimiento;
    }

    /**
     * @param mixed $nacimiento
     */
    public function setNacimiento($nacimiento)
    {
        $this->nacimiento = $nacimiento;
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
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getCi()
    {
        return $this->ci;
    }

    /**
     * @param mixed $ci
     */
    public function setCi($ci)
    {
        $this->ci = $ci;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * @param mixed $ciudad
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
    }

    /**
     * @return mixed
     */
    public function getSueldo()
    {
        return $this->sueldo;
    }

    /**
     * @param mixed $sueldo
     */
    public function setSueldo($sueldo)
    {
        $this->sueldo = $sueldo;
    }

    public function vertodos(){
        $conexion = new Conexion();
        $query = $conexion->prepare("select * from empleado order by nombre");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function insertarEmpleado(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO empleado(nombre,telefono,nacimiento,ciudad,email,sueldo,ci)
                                    VALUES(:nombre, :telefono, :nacimiento, :ciudad, :email, :sueldo, :ci);");
        $query->execute(array('nombre' => $this->getNombre(),
            'telefono' => $this->getTelefono(),
            'nacimiento' => $this->getNacimiento(),
            'ciudad' => $this->getCiudad(),
            'email' => $this->getEmail(),
            'sueldo' => $this->getSueldo(),
            'ci' =>$this->getCi()));
        $conexion = null;
    }

    public function selectEmpleado(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT idempleado,nombre,telefono,nacimiento,ciudad,email,sueldo,ci FROM empleado WHERE idempleado = :id;");
        $query->execute(array('id' => $this->getIdempleado() ));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function updateEmpleado(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE empleado
                                    SET nombre = :nombre, telefono = :telefono, nacimiento = :nacimiento, ciudad = :ciudad,
                                    email = :email, sueldo = :sueldo, ci = :ci
                                    WHERE idempleado = :id;");
        $query->execute(array('nombre' => $this->getNombre(),
            'telefono' => $this->getTelefono(),
            'nacimiento' => $this->getNacimiento(),
            'ciudad' => $this->getCiudad(),
            'email' => $this->getEmail(),
            'sueldo' => $this->getSueldo(),
            'ci' => $this->getCi(),
            'id' => $this->getIdempleado()));
        return $query->rowCount();
        $conexion = null;
    }



}
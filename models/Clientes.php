<?php

/**
 * Created by PhpStorm.
 * User: Fede
 * Date: 17/5/2017
 * Time: 3:03 PM
 */
class Clientes{

    private $idCliente;
    private $cliente;
    private $ruc;
    private $contacto;
    private $telefono;
    private $email;
    private $localidad;
    private $celular;
    private $direccion;

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
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * @param mixed $cliente
     */
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;
    }

    /**
     * @return mixed
     */
    public function getRuc()
    {
        return $this->ruc;
    }

    /**
     * @param mixed $ruc
     */
    public function setRuc($ruc)
    {
        $this->ruc = $ruc;
    }

    /**
     * @return mixed
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * @param mixed $contacto
     */
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;
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
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * @param mixed $localidad
     */
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;
    }

    /**
     * @return mixed
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * @param mixed $celular
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }


    public function selectAll(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT idcliente,cliente,ruc,contacto,telefono,email,localidad,celular,direccion FROM cliente order by idcliente desc limit 100");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function insertCustomer(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO cliente(cliente,telefono,direccion,contacto,celular,email,ruc,localidad)
                                    VALUES(:cliente,:telefono,:direccion,:contacto,:celular,:email,:ruc,:ciudad);");
        $query->execute(array('cliente' => $this->getCliente(),
            'telefono' => $this->getTelefono(),
            'direccion' => $this->getDireccion(),
            'contacto' => $this->getContacto(),
            'celular' => $this->getCelular(),
            'email' => $this->getEmail(),
            'ruc' => $this->getRuc(),
            'ciudad'=>$this->getLocalidad()));
        return $query->rowCount();
        $conexion = null;
    }

    public function searchCustomer(){
        $conexion = new Conexion();
        $likeVar = "%" . $this->getCliente() . "%";
        $query = $conexion->prepare("SELECT * FROM cliente WHERE cliente LIKE :cliente ORDER BY cliente;");
        $query->execute(array('cliente' => $likeVar ));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function selectCustomerId(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT * FROM cliente WHERE idcliente = :id;");
        $query->execute(array('id' => $this->getIdCliente()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function selectCustomerMaxId(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT max(idcliente) codigo FROM cliente;");
        $query->execute(array('id' => $this->getIdCliente()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function updateCustomer(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE cliente
                                    SET cliente = :cliente, direccion = :direccion, contacto = :contacto,
                                    telefono = :telefono, celular = :celular, email =:email, ruc=:ruc, localidad=:ciudad
                                    WHERE idcliente = :id;");
        $query->execute(array('cliente' => $this->getCliente(),
            'direccion' => $this->getDireccion(),
            'contacto' => $this->getContacto(),
            'telefono' => $this->getTelefono(),
            'celular' => $this->getCelular(),
            'email' => $this->getEmail(),
            'ruc' => $this->getRuc(),
            'ciudad'=>$this->getLocalidad(),
            'id' => $this->getIdCliente()));
        return $query->rowCount();
        $conexion = null;
    }

    public function deleteCustomer(){
        $conexion = new Conexion();
        $query = $conexion->prepare("DELETE FROM cliente WHERE idcliente=:id;");
        $query->execute(array('id' => $this->getIdCliente()));
        return $query->rowCount();
        $conexion = null;
    }
    

    
}
<?php

/**
 * Created by PhpStorm.
 * User: FedeXavier
 * Date: 13/03/2017
 * Time: 03:28 PM
 */
class Proveedor{

    private $idproveedor;
    private $proveedor;
    private $contacto;
    private $telefono;
    private $email;
    private $celular;
    private $direccion;
    private $ruc;
    private $ciudad;

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
    public function getProveedor()
    {
        return $this->proveedor;
    }

    /**
     * @param mixed $proveedor
     */
    public function setProveedor($proveedor)
    {
        $this->proveedor = $proveedor;
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

    public function selectAll(){
        $conexion = new Conexion();
        $query = $conexion->prepare("select * from proveedor order by proveedor asc limit 100");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function insertProvider(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO proveedor(proveedor,telefono,direccion,contacto,celular,email,ruc,ciudad)
                                    VALUES(:proveedor,:telefono,:direccion,:contacto,:celular,:email,:ruc,:ciudad);");
        $query->execute(array('proveedor' => $this->getProveedor(),
            'telefono' => $this->getTelefono(),
            'direccion' => $this->getDireccion(),
            'contacto' => $this->getContacto(),
            'celular' => $this->getCelular(),
            'email' => $this->getEmail(),
            'ruc' => $this->getRuc(),
            'ciudad'=>$this->getCiudad()));
        return $query->rowCount();
        $conexion = null;
    }

    public function searchProvider(){
        $conexion = new Conexion();
        $likeVar = "%" . $this->getProveedor() . "%";
        $query = $conexion->prepare("SELECT * FROM proveedor WHERE proveedor LIKE :proveedor ORDER BY proveedor;");
        $query->execute(array('proveedor' => $likeVar ));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function selectProviderId(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT * FROM proveedor WHERE idproveedor = :id;");
        $query->execute(array('id' => $this->getIdproveedor()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function selectProviderMaxId(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT max(idproveedor) codigo FROM proveedor;");
        $query->execute(array('id' => $this->getIdproveedor()));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function updateProvider(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE proveedor
                                    SET proveedor = :proveedor, direccion = :direccion, contacto = :contacto,
                                    telefono = :telefono, celular = :celular, email =:email, ruc=:ruc, ciudad=:ciudad
                                    WHERE idproveedor = :id;");
        $query->execute(array('proveedor' => $this->getProveedor(),
            'direccion' => $this->getDireccion(),
            'contacto' => $this->getContacto(),
            'telefono' => $this->getTelefono(),
            'celular' => $this->getCelular(),
            'email' => $this->getEmail(),
            'ruc' => $this->getRuc(),
            'ciudad'=>$this->getCiudad(),
            'id' => $this->getIdproveedor()));
        return $query->rowCount();
        $conexion = null;
    }

    public function deleteProvider(){
        $conexion = new Conexion();
        $query = $conexion->prepare("DELETE FROM proveedor WHERE idproveedor=:id;");
        $query->execute(array('id' => $this->getIdproveedor()));
        return $query->rowCount();
        $conexion = null;
    }




}
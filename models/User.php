<?php

/**
 * Created by PhpStorm.
 * User: FedeXavier
 * Date: 24/08/2016
 * Time: 03:25 PM
 */
class User{

    private $iduser;
    private $user;
    private $password;
    private $imagen;
    private $idrol;
    private $rol;

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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * @param mixed $imagen
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    /**
     * @return mixed
     */
    public function getIdrol()
    {
        return $this->idrol;
    }

    /**
     * @param mixed $idrol
     */
    public function setIdrol($idrol)
    {
        $this->idrol = $idrol;
    }

    /**
     * @return mixed
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * @param mixed $rol
     */
    public function setRol($rol)
    {
        $this->rol = $rol;
    }



    public function verUser(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT u.iduser,u.user,r.rol FROM user u, rol r WHERE u.idrol=r.idrol");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function insertarUser(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO user (user,password,estado,idrol) VALUES (:usuario,:pass,'A',:idrol)");
        $query->execute(array('usuario' => $this->getUser(),
            'pass' => $this->getPassword(),
            'idrol' => $this->getIdrol()));
        $conexion = null;
    }

    public function selectUser(){
        $conexion = new Conexion();
        $query = $conexion->prepare("SELECT * FROM user WHERE iduser = :id;");
        $query->execute(array('id' => $this->getIduser() ));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

   /* public function updateUser(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE user SET usuario=:usuario, nombre=:nombre, contrasena=:contrasena WHERE iduser= :id;");
        $query->execute(array('usuario' => $this->getUsuario(),
            'nombre' => $this->getNombre(),
            'contrasena' => $this->getContrasena(),
            'id' => $this->getIduser()));
        return $query->rowCount();
        $conexion = null;
    }*/
}
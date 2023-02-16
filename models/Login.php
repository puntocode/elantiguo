<?php

/**
 * Created by PhpStorm.
 * User: FedeXavier
 * Date: 13/07/2016
 * Time: 11:08 PM
 */
class Login{

    public $user;
    public $pass;
    public $result;

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
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
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param mixed $pass
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    public function consult(){
        try{
            $conexion = new Conexion();
            $query = $conexion->prepare("SELECT iduser,user,password,imagen,idrol FROM user WHERE user = :usuario AND password = :pass");
            $query->execute(array(':usuario' => $this->getUser(), ':pass' => $this->getPass()));
            $this->result = $query->fetch(PDO::FETCH_ASSOC);
            $conexion = null;
        }catch (Exception $e) {
            echo 'Exception: ' . $e->getMessage();
        }
    }


    public function check(){
        if(!empty($this->result)){
            $_SESSION['session'] = $this->result;
            return true;
        }else{
            return false;
        }
    }

}
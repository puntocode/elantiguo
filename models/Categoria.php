<?php

/**
 * Created by PhpStorm.
 * User: Fede
 * Date: 3/5/2017
 * Time: 10:37 AM
 */
class Categoria
{

    private $idcategoria;
    private $categoria;

    /**
     * @return mixed
     */
    public function getIdcategoria()
    {
        return $this->idcategoria;
    }

    /**
     * @param mixed $idcategoria
     */
    public function setIdcategoria($idcategoria)
    {
        $this->idcategoria = $idcategoria;
    }

    /**
     * @return mixed
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @param mixed $categoria
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }


    public function vertodos(){
        $conexion = new Conexion();
        $query = $conexion->prepare("select * from categoria order by categoria");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        $conexion = null;
    }

    public function insertarCategoria(){
        $conexion = new Conexion();
        $query = $conexion->prepare("INSERT INTO categoria(categoria)
                                    VALUES(:categoria);");
        $query->execute(array('categoria' => $this->getCategoria()));
        $conexion = null;
    }

    public function updateCategoria(){
        $conexion = new Conexion();
        $query = $conexion->prepare("UPDATE categoria
                                    SET categoria = :categoria
                                    WHERE idcategoria = :id;");
        $query->execute(array('categoria' => $this->getCategoria(),
            'id' => $this->getIdcategoria()));
        return $query->rowCount();
        $conexion = null;
    }


    public function deleteCategoria(){
        $conexion = new Conexion();
        $query = $conexion->prepare("DELETE FROM categoria WHERE idcategoria = :id;");
        $query->execute(array('id' => $this->getIdcategoria()));
        return $query->rowCount();
        $conexion = null;
    }
}
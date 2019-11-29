<?php
//modelo
    $database=null;
    $servidor= "localhost"; 
    $usuario= "server"; 
    $clave= "Server01%";  
    $bd= "myBDPDO";
    $error=false;

    try {
            $database = new PDO("mysql:host=$servidor;dbname=$bd;charset=utf8", $usuario, $clave);
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }
    catch(PDOException $e){
        global $error;
        $error = true;
    }
    function sql_insert($nombre, $apellidos, $tlf, $email){
        global $database;
        $sql='INSERT INTO alumnos (`NOMBRE`, `APELLIDOS`, `TELEFONO`, `CORREO`) VALUES (:nombre , :apellidos , :tlf , :email)';
        $stmt=$database->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':tlf', $tlf);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt;
    }
    function sql_query($value){
        global $database;
        $value="%{$value}%";
        $sql='SELECT * FROM `alumnos` WHERE `CODIGO` LIKE :value OR `NOMBRE` LIKE :value OR `APELLIDOS` LIKE :value OR `TELEFONO` LIKE :value OR `CORREO` LIKE :value';
        $stmt=$database->prepare($sql);
        $stmt->bindParam(':value', $value);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt;
    }
    function sql_delete($ids){
        global $database; 
        $result;
        foreach($ids as $id){
            $sql='DELETE FROM alumnos WHERE CODIGO = :id';
            $stmt=$database->prepare($sql);
            $stmt->bindParam(':id', $id);
            $result[] = $stmt->execute();
        }
    }
    function sql_update($ids, $nombre, $apellidos, $tlf, $email){
        global $database;
        foreach($ids as $key=>$id){ 
            $sql='UPDATE `alumnos` SET `NOMBRE`= :nombre ,`APELLIDOS`= :apellidos,`TELEFONO`= :tlf ,`CORREO`= :email WHERE `CODIGO`= :codigo ;';
            $stmt=$database->prepare($sql);
            $stmt->bindParam(':nombre', $nombre[$key], PDO::PARAM_STR);
            $stmt->bindParam(':apellidos', $apellidos[$key], PDO::PARAM_STR);
            $stmt->bindParam(':tlf', $tlf[$key], PDO::PARAM_STR);
            $stmt->bindParam(':email', $email[$key], PDO::PARAM_STR);
            $stmt->bindParam(':codigo', $id);
            $stmt->execute();
        }
    }
    
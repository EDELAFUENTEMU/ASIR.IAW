<?php
//modelo
    $database=null;
    $servidor= "localhost"; 
    $usuario= "server"; 
    $clave= "Server01%";  
    $bd= "myBDPDO";

    try {
            $database = new PDO("mysql:host=$servidor;dbname=$bd;charset=utf8", $usuario, $clave);
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        echo $e->getMessage();
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
        $sql='SELECT * FROM `alumnos` WHERE `NOMBRE` LIKE :value OR `APELLIDOS` LIKE :value OR `TELEFONO` LIKE :value OR `CORREO` LIKE :value';
        $stmt=$database->prepare($sql);
        $stmt->bindParam(':value', $value);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt;
    }
    function sql_delete($ids){
        global $database;
        foreach($ids as $id){
            $sql='DELETE FROM alumnos WHERE CODIGO = :id';
            $stmt=$database->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
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


///controlador
    function query($value){ 
        $stmt = sql_query($value);
        
        global $table;
        $table='<table>';
        $flag_cabecera=true; 
        
        while ($row = $stmt->fetch()){
            if($flag_cabecera){ #cabecera de la tabla
                $flag_cabecera=false;
                $table.="<tr><td>Eliminar</td><td>Actualizar</td>";
                foreach (array_keys($row) as $key){
                    $table .= "<td>$key</td>";
                }
                $table.='</tr>';
            }
            $table.="<tr>"
                ."<td><input type='checkbox' name='delete[]' value=".$row['CODIGO'].">"
                ."<td><input type='checkbox' name='update[]' value=".$row['CODIGO']." onclick='habilitar(".$row['CODIGO'].",this);'>";
            foreach ($row as $key => $valor){
                if($key == 'CODIGO'){
                    $table .= "<td><input type='text' name='".$key."[]' data-id=".$row['CODIGO']." value='".$valor."' disabled='disabled' readonly></td>";
                }else{
                    $table .= "<td><input type='text' name='".$key."[]' data-id=".$row['CODIGO']." value='".$valor."' disabled='disabled'></td>";
                }   
            }
            $table.='</tr>';
        }
    }
    function delete($ids){ //funciona
         sql_delete($ids);
         query('');
    }
    function insert($nombre, $apellidos, $tlf, $mail){ 
         $result = sql_query($mail); 
         var_dump($result->fetch());
         if($result->fetch()==false){
             var_dump($result->fetch());
             echo "erro ese email ya existe";  
         }else{
             var_dump($result->fetch());
             sql_insert($nombre, $apellidos, $tlf, $mail);
         }
         query('');
    }
    function update($ids, $nombre, $apellidos, $tlf, $mail ){
        sql_update($ids, $nombre, $apellidos, $tlf, $mail);
        query('');
    }

if(isset($_POST['btnEnviar'])){
	switch ($_POST['estado']){
		case 'insert':
            insert($_POST['nombre'], $_POST['apellidos'], $_POST['telefono'], $_POST['email']);
			break;
		case 'delete':
            delete($_POST['delete']);
			break;
		case 'query':
            query($_POST['nombre']);
			break;
		case 'update':
            update($_POST['CODIGO'],$_POST['NOMBRE'],$_POST['APELLIDOS'],$_POST['TELEFONO'],$_POST['CORREO']);
			break;
	}
}else{
    query('');
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            form{
                width:100%;
            }
			form label{
				display: inline;
			}
            .rigth{
                float: right;
            }
        </style>
    </head>
    <script type="text/javascript">
        function habilitar(id,elementDOM) {
            var inputs = document.getElementsByTagName('table')[0].getElementsByTagName('input');
            for (let elemento of inputs){
                if(elemento.dataset.id == id){
                    if(elementDOM.checked==true){
                        elemento.disabled=false;
                    }else{
                        elemento.disabled=true;
                    }
                }
            }
        }
    </script>
    <body>
        <h1>Alumnos</h1>
        <form action="" method="post" name='default'>
			<fieldset>
				<legend>Tipo de Operacion</legend>
                <input type="radio" name="estado" value="insert">Insertar</input>
				<input type="radio" name="estado" value="query" checked>Consultar</input>
                <input type="radio" name="estado" value="delete">Eliminar</input>
                <input type="radio" name="estado" value="update">Actualizar</input>
			</fieldset>
            <fieldset>
                <legend>Datos del alumno</legend>
                <label>Nombre del alumno:</label>
                <input type="text" name="nombre">
                 <label>Apellidos del alumno:</label>
                <input type="text" name="apellidos">
                 <label>Teléfono:</label>
                <input type="text" name="telefono">
                 <label>mail de contacto:</label>
                <input type="email" name="email" >
                <input type="submit" name="btnEnviar" value="Enviar">
            </fieldset>
         
        <fieldset>
            <legend style="text-align:right">Alumnos</legend>
            <?php echo $table; ?>
        </fieldset>  
        </form>     
    </body>
</html>

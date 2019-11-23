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
        alert($e->getMessage(),4);
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
        $table='<table class="table table-striped table-dark table-hover table-sm">';
        $flag_cabecera=true; 
        
        while ($row = $stmt->fetch()){
            if($flag_cabecera){ #cabecera de la tabla
                $flag_cabecera=false;
                $table.="<tr><th></th>";
                foreach (array_keys($row) as $key){
                    $table .= "<th>$key</th>";
                }
                $table.='</tr>';
            }
            $table.="<tr><td><input type='checkbox' class='' name='ids[]' value=".$row["CODIGO"]." id=".$row["CODIGO"]."></td>";
                        /*."<div class='custom-control custom-checkbox mb-3 inline'>"
                            ."<input type='checkbox' class='custom-control-input' name='ids[]' value=".$row["CODIGO"]." id=".$row["CODIGO"].">"
                            ."<label class='custom-control-label' for='".$row["CODIGO"]."' onclick='habilitar(".$row['CODIGO'].",this);'></label>"
                        ."</div></td>";*/
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
         $num = $result->rowCount();
         if($result->rowCount() < 1){ #comprueba que no exista un usuario con ese email
             sql_insert($nombre, $apellidos, $tlf, $mail);
         }else{
             alert ('El usuario ya existe. Insercion rechazada',3);
         }
         query('');
    }
    function update($ids, $nombre, $apellidos, $tlf, $mail ){
        sql_update($ids, $nombre, $apellidos, $tlf, $mail);
        query('');
    }
    
    
        
    $alert='';
    function alert($msg,$grav){
        $style;
        switch ($grav){
            case 1: // ok
                $msg = '<strong>Success!</strong> '.$msg;
                $style='alert-success';
            break;
            case 2: //advertencia
                $msg = '<strong>Advertencia!</strong> '.$msg;
                $style='alert-warning';
            break;
            case 3: //peligro
                $msg = '<strong>Error!</strong> '.$msg;
                $style='alert-warning';
            break;
            case 4: //dark --problemas de bd
                $msg = '<strong>Dark Code!</strong> '.$msg;
                $style='alert-dark';
            break;
        }
        global $alert; 
        $alert = "<div class='container mt-2 alert $style alert-dismissible fade show'>"
            ."<button type='button' class='close' data-dismiss='alert'>×</button>$msg</div>";
    }




if(isset($_POST['btnEnviar'])&&$error==false){
	switch ($_POST['estado']){
		case 'insert':
            insert($_POST['nombre'], $_POST['apellidos'], $_POST['telefono'], $_POST['email']);
			break;
		case 'delete':
            delete($_POST['ids']);
			break;
		case 'query':
            query($_POST['nombre']);
			break;
		case 'update':
            update($_POST['CODIGO'],$_POST['NOMBRE'],$_POST['APELLIDOS'],$_POST['TELEFONO'],$_POST['CORREO']);
			break;
	}
}elseif($error==false){
    query('');
}else{
   alert($e->getMessage(),4);
   $table='<span class="display-2 text-center d-block mt-4"><b>Mayday Houston!</b><br><span class="display-4">  Perdimos la conexión.</span><span>';
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <style>
            body{
                background-image: url('http://wallpapers.org.es/wp-content/uploads/2012/11/fondo-degradado-1024x640.jpg');
                background-size: cover;
                background-repeat: no-repeat;
                
            }
            
            nav input[type=radio]{
                display: none;
            }
            div table{
                overflow: auto;
                max-height: 70vh;
            }
            table input[type=text]{
                background: none;
                color: white;
                border: 0;
            }
            table th{
                background-color: cadetblue;
            }
            
            .btn-primary .badge {
                color: #337ab7;
                background-color: #fff;
            }

            .badge {
                display: inline-block;
                min-width: 10px;
                padding: 3px 7px;
                font-size: 12px;
                font-weight: 700;
                line-height: 1;
                color: #fff;
                text-align: center;
                white-space: nowrap;
                vertical-align: middle;
                background-color: #777;
                border-radius: 10px;
            }
        </style>
    </head>
    <script type="text/javascript">
        function habilitar(id,elementDOM) {
            if(document.getElementById('rUpdate').checked){ //si el tipo de operacion es actualizar                
                var inputs = document.getElementsByTagName('table')[0].getElementsByTagName('input');
                for (let elemento of inputs){
                    if(elemento.dataset.id == id){
                        if(elementDOM.checked==true){
                            document.getElementsByClassName('badge')[0].text++
                            elemento.disabled=false;
                            elemento.style.backgroundColor="red";
                        }else{
                            document.getElementsByClassName('badge')[0].text--
                            elemento.disabled=true;
                        }
                    }
                }
            }
        }
        function nameBtn(name){
            switch (name){
                case 'Consultar':
                    document.getElementsByName('btnEnviar')[0].innerHTML= 'Buscar';
                    for(item of document.getElementsByClassName('search_insert')){item.style.display='inline';}
                    for(item of document.getElementsByClassName('insert')){item.style.display='none';}
                    for(item of document.getElementsByName('ids[]')){item.disabled=true}
                    break;
                case 'Insertar':
                    document.getElementsByName('btnEnviar')[0].innerHTML= 'Insertar';
                    for(item of document.getElementsByClassName('insert')){item.style.display='inline';}
                    for(item of document.getElementsByName('ids[]')){item.disabled=true}
                    break;
                case 'Actualizar':
                    document.getElementsByName('btnEnviar')[0].innerHTML= 'Modificar <span class="badge">0</span>';
                    for(item of document.querySelectorAll('.insert, .search_insert')){item.style.display='none';}
                    for(item of document.getElementsByName('ids[]')){item.disabled=false}
                    break;
                case 'Eliminar':
                    document.getElementsByName('btnEnviar')[0].innerHTML= 'Eliminar <span class="badge">0</span>';
                    for(item of document.querySelectorAll('.insert, .search_insert')){item.style.display='none';}
                    for(item of document.getElementsByName('ids[]')){item.disabled=false}
                    break;
            }
        }
    </script>
    <body>
        <!--header-->
        <nav class="navbar navbar-dark bg-dark">   
            <div class="container">
                <h1 class="text-white">Alumnos</h1>
                <div class=" class="btn-group btn-group-toggle" data-toggle="buttons"">
                  <label class="btn btn-secondary active btn-rounded form-check-label" onclick="nameBtn('Insertar')">
                    <input form="default" class="form-check-input" type="radio" name="estado" value="insert" onclick="nameBtn('Insertar')" checked> Insertar
                  </label>
                  <label class="btn btn-secondary  btn-rounded form-check-label" onclick="nameBtn('Consultar')">
                    <input form="default" class="form-check-input" type="radio" name="estado" value="query" onclick="nameBtn('Consultar')"> Consultar
                  </label>
                  <label class="btn btn-secondary  btn-rounded form-check-label" onclick="nameBtn('Eliminar')">
                    <input form="default"  class="form-check-input" type="radio" name="estado" value="delete" > Eliminar
                  </label>
                  <label class="btn btn-secondary  btn-rounded form-check-label" onclick="nameBtn('Actualizar')">
                    <input id="rUpdate" class="form-check-input"  form="default" type="radio" name="estado" value="update" > Actualizar
                  </label>
                </div>
            </div>
        </nav>
        <!-- div de alerta -->
        <?php echo $alert; ?>
        
          
           <form action="" method="post" id='default'class="container">
               <div class="form-row rounded mt-3 p-2 shadow bg-white justify-content-end">
                    <div class="col search_insert">
                        <input class="form-control" type="text" name="nombre" placeholder="Nombre">
                    </div>    
                    <div class="col insert">
                        <input class="insert form-control" type="text" name="apellidos" placeholder="Apellidos">
                    </div>  
                    <div class="col insert">
                        <input class="insert form-control" type="text" name="telefono" placeholder="Teléfono">
                    </div>  
                    <div class="col insert">
                        <input class="insert form-control" type="email" name="email" placeholder="Email">
                    </div>  
                    <div class="col col-2">
                        <button class="btn btn-primary col-md-12" type="submit" name="btnEnviar" form="default">
                            Insertar
                        </button>       
                    </div>  
               </div>  
            <div class="mt-4 overflow-auto" style="max-height:70vh; overflow:auto">
                <?php echo $table; ?>
            </div>  
        </form>    
        <!-- Código de alerta-->
        
    </body>
</html>

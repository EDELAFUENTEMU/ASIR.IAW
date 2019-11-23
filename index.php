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
    
    

///controlador
    $alert=''; $table='<span class="display-2 text-center d-block mt-4"><b>Mayday Houston!</b><br><span class="display-4">Error 40X.y.</span><span>';

    //main. recibe parametros por post
    if(isset($_POST['btnEnviar'])&&$error==false){ 
        switch ($_POST['estado']){
            case 'insert':
                $arr = ['nombre','apellidos','telefono','email'];
                $exp = ['/[A-Za-z\s]+/','/[A-Za-z\s]+/','/[0-9]{8}/','/[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.([a-zA-Z]{2,4})+$/'];
                foreach($arr as $key =>$item){
                    if (!preg_match($exp[$key], $_POST[$item])){
                        alert("El campo $item no cumple la condición o esta vacio",2); 
                        break 2;
                    }
                }
                insert($_POST['nombre'], $_POST['apellidos'], $_POST['telefono'], $_POST['email']);
                break;
            case 'delete':
                foreach ($_POST['ids'] as $item){
                    if (!preg_match('/^[0-9]+$/', $item)){
                        alert("Error validación identificadores.",3); 
                        break 2;
                    }
                }
                delete($_POST['ids']);
                break;
            case 'query':
                if (!preg_match('/^[A-Za-z0-9\s]+$/', $_POST['nombre'])){
                    alert("Caractere no aceptado de busqueda.",2); 
                    break;
                }
                query($_POST['nombre']);
                break;
            case 'update':
                $arr = ['CODIGO','NOMBRE','APELLIDOS','TELEFONO','CORREO'];
                $exp = ['/^[0-9]+$','/[A-Za-z\s]+/','/[A-Za-z\s]+/','/[0-9]{8}/','/[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.([a-zA-Z]{2,4})+$/'];
                foreach($arr as $key =>$item){
                    if (!preg_match($exp[$key], $_POST[$item])){
                        alert("El campo $item no cumple la condición o esta vacio",2); 
                        break 2;
                    }
                }
                update($_POST['CODIGO'],$_POST['NOMBRE'],$_POST['APELLIDOS'],$_POST['TELEFONO'],$_POST['CORREO']);
                break;
        }
    }elseif($error==false){
        query('');
    }else{
       alert($e->getMessage(),4);
       $table='<span class="display-2 text-center d-block mt-4"><b>Mayday Houston!</b><br><span class="display-4">  Perdimos la conexión.</span><span>';
    }

    function query($value){ 
        $stmt = sql_query($value);
        
        global $table;
        $table='<table class="table table-striped table-dark table-sm">';
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
                $table.="<tr><td><input type='checkbox' disabled name='ids[]' value=".$row["CODIGO"]." id=".$row["CODIGO"]." onclick='habilitar(".$row['CODIGO'].",this);'></td>";
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
             $r = sql_insert($nombre, $apellidos, $tlf, $mail);
             alert ("Se ha insertado a $nombre exitosamente",1);
         }else{
             alert ('El usuario ya existe',3);
         }
         query('');
    }
    function update($ids, $nombre, $apellidos, $tlf, $mail ){
        sql_update($ids, $nombre, $apellidos, $tlf, $mail);
        query('');
    }

    //ocupa de mostrar mensajes
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
                $msg = '<strong>Black Alert!</strong> '.$msg;
                $style='alert-dark';
            break;
        }
        global $alert; 
        $alert .= "<div class='container mt-2 alert $style alert-dismissible fade show'>"
            ."<button type='button' class='close' data-dismiss='alert'>×</button>$msg</div>";
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
                background-color: #0062cc;
            }
            .selectRow{
                background-color: skyblue !important;
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
            var inputs = document.getElementsByTagName('table')[0].getElementsByTagName('input'); //todos los input del form
            $numT = parseInt(document.getElementsByClassName('badge')[0].textContent,10); //numero de checkbox seleccionados
            
            if(elementDOM.checked==true){ //indistintamente tanto para delete como update. A la insignia le suma o le quita
                document.getElementsByClassName('badge')[0].textContent = $numT + 1;
            }else{
                document.getElementsByClassName('badge')[0].textContent = $numT - 1;
            }
            
            if(document.getElementById('rUpdate').checked){ //solo para actualizar                
                for (let elemento of inputs){
                    if(elemento.dataset.id == id){
                        if(elementDOM.checked==true){
                            elemento.disabled=false; //habilito las casillas de escritura
                            elemento.parentNode.parentNode.classList.add('selectRow');
                        }else{
                            elemento.disabled=true;
                            elemento.parentNode.parentNode.classList.remove('selectRow');
                        }
                    }
                }
            }
            
        }
        function nameBtn(name){ 
            switch (name){
                case 'Consultar':
                    document.getElementsByName('btnEnviar')[0].innerHTML= 'Buscar'; //cambia el contenido del boton
                    for(item of document.getElementsByClassName('search_insert')){item.style.display='inline';} //muestra campo nombre->search & insert
                    for(item of document.getElementsByClassName('insert')){item.style.display='none';} //muestra todos los campos
                    for(item of document.getElementsByName('ids[]')){item.disabled=true} //bloquea los checkbox
                    break;
                case 'Insertar':
                    document.getElementsByName('btnEnviar')[0].innerHTML= 'Insertar'; //cambia el contenido del boton
                    for(item of document.getElementsByClassName('insert')){item.style.display='inline';} //muestra todos los campos
                    for(item of document.getElementsByName('ids[]')){item.disabled=true} //bloquea los checkbox
                    break;
                case 'Actualizar':
                    document.getElementsByName('btnEnviar')[0].innerHTML= 'Modificar <span class="badge">0</span>'; //cambia el contenido del boton
                    for(item of document.querySelectorAll('.insert, .search_insert')){item.style.display='none';} //quita los campos del formulario insert
                    for(item of document.getElementsByName('ids[]')){item.disabled=false} //desbloquea los checkbox
                    for(item of document.getElementsByTagName('table')[0].getElementsByTagName('input')){item.checked=false}
                    for(item of document.getElementsByTagName('tr')){item.classList.remove('selectRow');}
                    break;
                case 'Eliminar':
                    document.getElementsByName('btnEnviar')[0].innerHTML= 'Eliminar <span class="badge">0</span>'; //cambia el contenido del boton
                    for(item of document.querySelectorAll('.insert, .search_insert')){item.style.display='none';} //quita los campos del formulario insert
                    for(item of document.getElementsByName('ids[]')){item.disabled=false} //desbloquea los checkbox
                    for(item of document.getElementsByTagName('tr')){item.classList.remove('selectRow');}
                    break;
            }
        }
    </script>
    <body>
        <!--header-->
        <nav class="navbar navbar-dark bg-dark">   
            <div class="container">
                <h1 class="display-4">Alumnos</h1>
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

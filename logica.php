<?php
///controlador
    $alert='';$table='';

    //main. recibe parametros por post y validación en el lado del servidor
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
                if(isset($_POST['ids'])){
                    foreach ($_POST['ids'] as $item){
                        if (!preg_match('/^[0-9]+$/', $item)){
                            alert("Error validación identificadores.",3); 
                            break 2;
                        }
                    }
                    delete($_POST['ids']);
                    break;
                }else{
                    alert("No has seleccionado ningún alumno.",2);
                }
                
            case 'query':
                if (!preg_match('/^[A-Za-z0-9\s]+$/', $_POST['nombre'])){
                    alert("Caractere no aceptado de busqueda.",2); 
                    break;
                }
                query($_POST['nombre']);
                break;
            case 'update':
                $arr = ['CODIGO','NOMBRE','APELLIDOS','TELEFONO','CORREO'];
                $exp = ['/^[0-9]+$/','/[A-Za-z\s]+/','/[A-Za-z\s]+/','/[0-9]{8}/','/[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.([a-zA-Z]{2,4})+$/'];
                
                foreach($arr as $key =>$item){
                    if (isset($_POST[$item])){
                        foreach($_POST[$item] as $valor){
                            if(!preg_match($exp[$key], $valor)){
                               alert("El campo $item no cumple la condición o esta vacio",2); 
                               break 3; 
                            }
                        }
                    }else{
                        alert("El campo $item no se ha enviado correctamente",2); 
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
                $table.="<tr><th class='w-25'></th>";
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

    //se ocupa de mostrar mensajes
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
                $style='alert-danger';
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



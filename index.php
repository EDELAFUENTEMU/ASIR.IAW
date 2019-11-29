<?php
##vista
	$table='';$alerta='';
	include_once ('modelo.php');
	include_once ('logica.php');

	
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Eduardo" />
        <title>AppWeb</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>-->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.min.css" integrity="sha256-BJ/G+e+y7bQdrYkS2RBTyNfBHpA9IuGaPmf9htub5MQ=" crossorigin="anonymous" />
        <link rel="stylesheet" href="style.css">
        <script src="script.js"></script>

    </head>
    <body>
        <!--header-->
        <nav class="navbar navbar-dark bg-dark">   
            <div class="container">
                <h1 class="display-4">Alumnos <span class="oi oi-icon-name" title="icon name" aria-hidden="true"></span></h1>
                <div class=" class="btn-group btn-group-toggle" data-toggle="buttons"">
                  <label class="btn btn-secondary  btn-rounded form-check-label" onclick="nameBtn('Consultar')">
                    <input form="default" class="form-check-input" type="radio" name="estado" value="query" onclick="nameBtn('Consultar')"> <span class="oi oi-magnifying-glass"></span> Buscar
                  </label>
                  <label class="btn btn-secondary active btn-rounded form-check-label" onclick="nameBtn('Insertar')">
                    <input form="default" class="form-check-input" type="radio" name="estado" value="insert" onclick="nameBtn('Insertar')" checked> <span class="oi oi-plus"></span> Insertar
                  </label>
                  <label class="btn btn-secondary  btn-rounded form-check-label" onclick="nameBtn('Eliminar')">
                    <input form="default"  class="form-check-input" type="radio" name="estado" value="delete" ><span class="oi oi-trash"></span> Eliminar
                  </label>
                  <label class="btn btn-secondary  btn-rounded form-check-label" onclick="nameBtn('Actualizar')">
                    <input id="rUpdate" class="form-check-input"  form="default" type="radio" name="estado" value="update" > <span class="oi oi-pencil"></span> Modificar
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
        
    </body>
</html>

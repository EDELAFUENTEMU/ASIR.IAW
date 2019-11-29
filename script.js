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
function validacion(){
    var flag=true;
    var estado = document.forms[0].estado.value;
    if(estado == 'query' || estado == 'insert' || estado == 'delete' || estado == 'update' ){

        switch (estado){
            case 'query':
                var valor = document.forms[0].nombre.value;
                if( valor == null || valor.length == 0 || /^\s+$/.test(valor) ){
                    alert ('Error! Campo de busqueda vacio. Ingrese un valor valido');
                    flag=false;
                }
            break;
            case 'insert':
                var nombre = validarTxt(document.forms[0].nombre.value);
                var apellidos = validarTxt(document.forms[0].apellidos.value);
                var telefono = validarTlf(document.forms[0].telefono.value);
                var email=validarEmail(document.forms[0].email.value);
                console.log(nombre,apellidos,telefono,email);
                if( !nombre || !apellidos || !telefono || !email ){
                    alert('Error en los campos! Vuelva a intentarlo');
                    return false;
                }
            break;
            case 'delete':
                document.forms[0].ids.forEach(
                    function(id){
                        if(validarId(id.value)!=true){
                            alert('error procesar el id '+id.value);
                            return false;
                        }
                    });
            break;
        }
        return flag;
    }else{
        alert('error en el tipo de operacion');
        return false;
    }
    
   

}
 function validarTxt(valor){
        if(/^[a-zA-Z ]+$/.test(valor) ){
               return true;
        }
        return false;
    }
    function validarTlf(valor){
        if(/^[6|7|9][0-9]{8}$/.test(valor) ){
               return true;
        }
        return false;
    }
    function validarEmail(valor){
        if(/\b[\w\.-]+@[\w\.-]+\.\w{2,4}\b/.test(valor)){
                return true;
        }
        return false;
    }
    function validarId(valor){
        if(/^[0-9]+$/.test(valor) ){
               return true;
        }
        return false;
    }
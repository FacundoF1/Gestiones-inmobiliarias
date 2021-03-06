$(function(){
	//enfocamos el campo para digitar nombres (cuestion de usabilidad)
	$('#txtNombre').focus();
	
	//evento al hacer clic en el boton agregar
	$('#btnAgregar').on('click',function(){
		//obtenemos el nombre digitado por el usuario, y el limite establecido
		//con la funcion parseInt() convertimos de texto a numero
		var $txtNombre=$('#txtNombre'), iLimite=parseInt($('#selLimite').val());
		
		//verificamos que el campo nombre no este vacio
		if($.trim($txtNombre.val())!=''){
			//variable para contener la lista html
			var $ulLista;
			//si la lista html no existe entonces la agregamos al dom
			if(!$('#divLista').find('ul').length) $('#divLista').append('<ul/>');
			
			//obtenemos una instancia de la lista
			$ulLista=$('#divLista').find('ul');
			
			//verificamos el limite de elementos
			if($ulLista.find('li').length<iLimite || iLimite==0){
				//creamos el item que va a contener el nombre y el boton eliminar
				var $liNuevoNombre=$('<li/>').html('<a class="clsEliminarElemento">&nbsp;</a>'+$.trim($txtNombre.val()));
				
				//verificamos la posicion en la que debemos agregar el nuevo elemento (inicio o final de la lista)
				if($('#chkAgregarAlInicio').is(':checked')){
					//agregamos el elemento al inicio (con prepend)
					$ulLista.prepend($liNuevoNombre);
				}else{
					//agregamos el elemento al final de la lista (con append)
					$ulLista.append($liNuevoNombre);
				}
			//no se pueden agregar mas elementos, debido al limite establecido
			}else{
				alert('No es posible agregar el elemento. Se permiten solamente '+iLimite+'.');
			}
		//el campo nombre esta vacio
		}else{
			alert('Por favor, digite el nombre que desea agregar a la lista.')
		}
		//limpiamos el campo nombre y lo enfocamos
		$txtNombre.val('').focus();
	});
	
	
	
	//al presionar <ENTER> sobre el campo txtNombre llamamos al boton (usabilidad otra vez)
	$('#txtNombre').on('keypress',function(eEvento){
		if(eEvento.which==13) $('#btnAgregar').trigger('click');
	});	
});

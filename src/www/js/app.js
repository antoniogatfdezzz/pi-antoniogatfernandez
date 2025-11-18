// JS central del proyecto. Mantener código mínimo y limpio.
// Mensajes informativos sin inundar la consola.
console.info('Aplicación Split-1 cargada correctamente.');

// Validación sencilla de formularios (ejemplo de mejora limpia)
document.addEventListener('submit', function(e){
	const form = e.target;
	if(form.matches('form') && form.querySelector('[name="usuario"]')){
		const usuario = form.querySelector('[name="usuario"]').value.trim();
		if(usuario.length < 3){
			e.preventDefault();
			alert('El nombre de usuario debe tener al menos 3 caracteres.');
		}
	}
});

// Marcar visualmente disponibilidad seleccionada (checkbox helper)
document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
	cb.addEventListener('change', () => console.info(`Disponibilidad: ${cb.name}=${cb.checked ? 'sí' : 'no'}`));
});

// Confirmación genérica para formularios de eliminación en listados
document.addEventListener('DOMContentLoaded', function(){
	document.querySelectorAll('form.form-eliminar').forEach(function(f){
		f.addEventListener('submit', function(e){
			if (!confirm('¿Eliminar este registro?')) { e.preventDefault(); }
		});
	});
});

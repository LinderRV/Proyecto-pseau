// Ajax submit para los formularios de autenticación
document.addEventListener('DOMContentLoaded', function() {
    // Configurar toggles de contraseña al cargar la página
    setupPasswordToggles();
    
    // Obtener todos los formularios con la clase 'ajax-form'
    const ajaxForms = document.querySelectorAll('.ajax-form');
    
    ajaxForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Mostrar indicador de carga
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Procesando...';
            
            // Limpiar errores previos
            clearFormErrors(form);
            
            const formData = new FormData(form);
            const url = form.getAttribute('action');
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }
                
                return response.json();
            })
            .then(data => {
                if (data && data.errors) {
                    // Mostrar errores
                    displayFormErrors(form, data.errors);
                    
                    // Restaurar el botón
                    restoreSubmitButton(submitButton, originalButtonText);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Restaurar el botón
                restoreSubmitButton(submitButton, originalButtonText);
            });
        });
    });
    
    // Configurar eventos de input para limpiar errores
    setupInputEventListeners();
});

/**
 * Configura los toggles de mostrar/ocultar contraseña
 */
function setupPasswordToggles() {
    // Toggle para campos de contraseña
    document.querySelectorAll('.toggle-password').forEach(toggleButton => {
        toggleButton.addEventListener('click', function() {
            togglePasswordVisibility(this);
        });
    });

    // Toggle para campos de confirmación de contraseña
    document.querySelectorAll('.toggle-confirm-password').forEach(toggleButton => {
        toggleButton.addEventListener('click', function() {
            togglePasswordVisibility(this);
        });
    });
}

/**
 * Cambia la visibilidad de un campo de contraseña
 */
function togglePasswordVisibility(toggleButton) {
    const passwordField = toggleButton.closest('.relative').querySelector('input[type="password"], input[type="text"]');
    const showIcon = toggleButton.querySelector('.show-password');
    const hideIcon = toggleButton.querySelector('.hide-password');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        showIcon.classList.add('hidden');
        hideIcon.classList.remove('hidden');
    } else {
        passwordField.type = 'password';
        showIcon.classList.remove('hidden');
        hideIcon.classList.add('hidden');
    }
}

/**
 * Limpia todos los errores de un formulario
 */
function clearFormErrors(form) {
    form.querySelectorAll('.text-red-500').forEach(error => {
        error.classList.add('hidden');
        error.textContent = '';
    });
    
    form.querySelectorAll('.border-red-500').forEach(input => {
        input.classList.remove('border-red-500');
    });
}

/**
 * Muestra los errores en el formulario
 */
function displayFormErrors(form, errors) {
    Object.keys(errors).forEach(field => {
        const errorElement = form.querySelector(`#${field}-error`);
        if (errorElement) {
            // Asegurarse de que se muestre el mensaje en español
            let errorMessage = errors[field][0];
            if (errorMessage === 'These credentials do not match our records.') {
                errorMessage = 'El correo electrónico o la contraseña son incorrectos.';
            }
            errorElement.textContent = errorMessage;
            errorElement.classList.remove('hidden');
        }
        
        const inputElement = form.querySelector(`#${field}`);
        if (inputElement) {
            inputElement.classList.add('border-red-500');
        }
    });
}

/**
 * Restaura el estado original del botón de envío
 */
function restoreSubmitButton(button, originalText) {
    button.disabled = false;
    button.innerHTML = originalText;
}

/**
 * Configura los event listeners para los inputs
 */
function setupInputEventListeners() {
    // Limpiar errores al escribir en los campos
    document.querySelectorAll('.form-input').forEach(input => {
        // Limpieza de errores
        input.addEventListener('input', function() {
            const fieldName = this.getAttribute('name');
            const errorElement = document.querySelector(`#${fieldName}-error`);
            if (errorElement) {
                errorElement.textContent = '';
                errorElement.classList.add('hidden');
            }
            this.classList.remove('border-red-500');
        });
        
        // Efectos visuales
        if (input.value.trim() !== '') {
            input.classList.add('active');
        }
        
        // Evento focus
        input.addEventListener('focus', function() {
            this.classList.add('active');
            this.closest('.relative')?.classList.add('focused');
        });
        
        // Evento blur
        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.remove('active');
            }
            this.closest('.relative')?.classList.remove('focused');
        });
    });
}
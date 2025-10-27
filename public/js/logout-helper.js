/* 
 * Logout helper script
 * This script ensures the logout functionality works correctly
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Logout helper initialized');
    
    // Verificar que los formularios de logout existen
    const logoutForm = document.getElementById('logout-form');
    const mobileLogoutForm = document.getElementById('mobile-logout-form');
    
    // Función para manejar el cierre de sesión manualmente
    window.handleLogout = function(mobile = false) {
        console.log('Handle logout triggered, mobile:', mobile);
        
        const form = mobile ? mobileLogoutForm : logoutForm;
        if (form) {
            console.log('Submitting logout form');
            form.submit();
        } else {
            console.error('Logout form not found!');
        }
    };
    
    // Debug helper
    window.checkDropdownState = function() {
        const dropdown = document.querySelector('[x-data="{ open: false }"]');
        if (dropdown && window.Alpine) {
            const openState = Alpine.$data(dropdown).open;
            console.log('Dropdown state:', openState);
            return openState;
        }
        return 'Alpine.js not initialized or dropdown not found';
    };
});
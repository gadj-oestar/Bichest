// public/js/crypto-dropdown.js
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('amount-input');
    const dropdown = document.getElementById('crypto-dropdown');
    
    input.addEventListener('focus', function() {
        dropdown.style.display = 'block';
    });

    input.addEventListener('blur', function() {
        setTimeout(function() {
            dropdown.style.display = 'none';
        }, 100); // Délai pour s'assurer que la sélection se fait avant de cacher le menu
    });

    dropdown.addEventListener('change', function() {
        // Optionnel: ajouter des actions lors de la sélection d'une option
    });
});

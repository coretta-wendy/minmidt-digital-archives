document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.document-card');
    
    cards.forEach(card => {
        // Animation au survol
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
            this.style.zIndex = '10';
        });
        
        // Retour à l'état initial
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.zIndex = '1';
        });
        
        // Clique pour la redirection
        card.addEventListener('click', function() {
            window.location.href = this.getAttribute('data-href');
        });
    });
});
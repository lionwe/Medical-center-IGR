document.addEventListener('DOMContentLoaded', function() {
    const preloader = document.getElementById('preloader');
    
    if (preloader) {
        // Hide preloader when page is fully loaded
        window.addEventListener('load', function() {
            setTimeout(function() {
                preloader.classList.add('hidden');
                
                // Remove preloader from DOM after animation
                setTimeout(function() {
                    preloader.remove();
                }, 500);
            }, 500);
        });
        
        // Fallback: hide after 3 seconds if load event doesn't fire
        setTimeout(function() {
            if (preloader && !preloader.classList.contains('hidden')) {
                preloader.classList.add('hidden');
                setTimeout(function() {
                    if (preloader && preloader.parentNode) {
                        preloader.remove();
                    }
                }, 500);
            }
        }, 3000);
    }
});

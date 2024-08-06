document.addEventListener('DOMContentLoaded', function() {
    const toggles = document.querySelectorAll('.applicant-toggle');
    toggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const details = this.parentElement.nextElementSibling;
            if (details.style.display === 'none' || details.style.display === '') {
                details.style.display = 'block';
            } else {
                details.style.display = 'none';
            }
        });
    });
});
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
    
    const editButton = document.querySelector('.edit-button');
    const saveButton = document.querySelector('.save-button'); // Updated class name
    const cancelButton = document.querySelector('.cancel-button'); // Updated class name
    const form = document.querySelector('.profile-form');
    const inputs = form.querySelectorAll('input, textarea');

    function toggleEditMode() {
        let isEditing = form.classList.contains('editing');
        inputs.forEach(input => input.readOnly = !isEditing);

        // Toggle visibility of buttons
        if (editButton) {
            editButton.style.display = isEditing ? 'none' : 'inline';
        }
        if (saveButton) {
            saveButton.style.display = isEditing ? 'inline' : 'none';
        }
        if (cancelButton) {
            cancelButton.style.display = isEditing ? 'inline' : 'none';
        }
    }

    if (editButton) {
        editButton.addEventListener('click', function() {
            form.classList.add('editing');
            toggleEditMode();
        });
    }

    if (cancelButton) {
        cancelButton.addEventListener('click', function() {
            form.classList.remove('editing');
            toggleEditMode();
        });
    }
    
    // Initialize the form state
    toggleEditMode();
});
// Scroll to Top Button
// Get the button
const scrollTopBtn = document.getElementById('scrollTopBtn');

// Show the button when scrolling down 100px from the top
window.onscroll = function() {
    if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
        scrollTopBtn.style.display = "block";
    } else {
        scrollTopBtn.style.display = "none";
    }
};

// Scroll to top when the button is clicked
scrollTopBtn.onclick = function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

document.addEventListener('DOMContentLoaded', () => {
    const images = document.querySelectorAll('.animate-on-scroll');
  
    const onScroll = () => {
      images.forEach((img) => {
        const rect = img.getBoundingClientRect();
        if (rect.top < window.innerHeight && rect.bottom > 0) {
          img.classList.add('active');
        }
      });
    };
  
    window.addEventListener('scroll', onScroll);
    onScroll(); // Trigger on load in case some images are already in view
  });
  

document.addEventListener('DOMContentLoaded', function() {
    // viewApplication js code
    const applicantButtons = document.querySelectorAll('.applicant-toggle');
    const applicantDetailsDiv = document.getElementById('applicant-details');
    
    applicantButtons.forEach(button => {
        button.addEventListener('click', function() {
            const applicantId = this.getAttribute('data-id');
            const status = this.getAttribute('data-status');

            console.log('ID: ', applicantId)

            const url = `/admin/viewApplication/${applicantId}`;
            console.log('Fetching URL:', url); // Log the URL being fetched
            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                return response.text().then(text => {
                    throw new Error('Network response was not ok: ' + text);
                });
                }
                return response.json();
            })
            .then(data => {
                console.log(data); 
                // Hide all details containers
                document.getElementById('applicant-details-pending').style.display = 'none';
                document.getElementById('applicant-details-approved').style.display = 'none';
                document.getElementById('applicant-details-rejected').style.display = 'none';

                // Determine which container to show based on the status
                let targetContainer;
                if (status === 'pending') {
                    targetContainer = document.getElementById('applicant-details-pending');
                } else if (status === 'approved') {
                    targetContainer = document.getElementById('applicant-details-approved');
                } else if (status === 'rejected') {
                    targetContainer = document.getElementById('applicant-details-rejected');
                }
                const birthdate = new Date(data.birthdate); // Convert the birthdate string to a Date object
                const today = new Date(); // Get the current date

                // Calculate the age
                let age = today.getFullYear() - birthdate.getFullYear();
                const monthDifference = today.getMonth() - birthdate.getMonth();
                const dayDifference = today.getDate() - birthdate.getDate();

                // Adjust age if the current date is before the birthdate this year
                if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
                    age--;
                }
                const addressParts = [
                    data.street_address,
                    data.city,
                    data.state,
                    data.country,
                    data.postal_code ? `- ${data.postal_code}` : '' // Add hyphen only if postal code exists
                ];
                
                // Filter out empty parts and join with commas
                const address = addressParts.filter(Boolean).join(', ');

                document.querySelector('.approve-btn').setAttribute('data-id', data.memberApplicationID);
                document.querySelector('.reject-btn').setAttribute('data-id', data.memberApplicationID);
                targetContainer.querySelector('a.btn-info').href = `/admin/applicationForm/${data.memberApplicationID}`;
                targetContainer.querySelector('#detail-name').textContent = data.first_name + " " + data.middle_name + " " + data.last_name;
                targetContainer.querySelector('#detail-age').textContent = age;
                targetContainer.querySelector('#detail-gender').textContent = data.gender;
                targetContainer.querySelector('#detail-phone_number').textContent = data.phone_number;
                targetContainer.querySelector('#detail-email_address').textContent = data.email_address;
                targetContainer.querySelector('#detail-address').textContent = address;
                targetContainer.querySelector('#detail-religion').textContent = data.religion;
                targetContainer.querySelector('#detail-citizenship').textContent = data.citizenship;
                targetContainer.querySelector('#detail-civil_status').textContent = data.civil_status;
                targetContainer.querySelector('#detail-college').textContent = data.college;
                targetContainer.querySelector('#detail-course').textContent = data.course;
                targetContainer.querySelector('#detail-year_level').textContent = data.year_level;
                targetContainer.querySelector('#detail-schoolID').textContent = data.schoolID;
                targetContainer.querySelector('#detail-high_school').textContent = data.high_school;
                targetContainer.querySelector('#detail-elementary').textContent = data.elementary;
                targetContainer.querySelector('#detail-reasons_for_joining').textContent = data.reasons_for_joining;
                
                // Display the correct container
                targetContainer.style.display = 'block';
                // Optional: Log what containers are being updated
                console.log('Updated:', targetContainer);
            })
            .catch(error => console.error('Error fetching applicant details:', error));

        });
    });
    
    // Event listeners for approve and reject buttons
    document.querySelectorAll('.approve-btn').forEach(button => {
        button.addEventListener('click', function () {
            const applicationID = this.getAttribute('data-id');

            Swal.fire({
                title: "Approve Application?",
                text: "Are you sure you want to approve this application?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, approve it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    approveApplication(applicationID);
                }
            });
        });
    });

    document.querySelectorAll('.reject-btn').forEach(button => {
        button.addEventListener('click', function () {
            const applicationID = this.getAttribute('data-id');

            Swal.fire({
                title: "Reject Application?",
                text: "Are you sure you want to reject this application?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, reject it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    rejectApplication(applicationID);
                }
            });
        });
    });

    // Functions to handle the API call
    function approveApplication(applicationID) {
        fetch(`/admin/approve-application/${applicationID}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF token for security
            },
            body: JSON.stringify({ applicationID: applicationID })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                Swal.fire({
                    title: "Success!",
                    text: data.message,
                    icon: "success",
                    confirmButtonColor: "#28a745",
                    confirmButtonText: "OK"
                }).then(() => {
                    removeApplicantFromList(applicantId); // Update UI after confirmation
                });
            }
        })
        .catch(error => {
            console.error('Error approving application:', error);
        });
    }

    function rejectApplication(applicationID) {
        fetch(`/admin/reject-application/${applicationID}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ applicationID: applicationID })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                Swal.fire({
                    title: "Success!",
                    text: data.message,
                    icon: "success",
                    confirmButtonColor: "#28a745",
                    confirmButtonText: "OK"
                }).then(() => {
                    removeApplicantFromList(applicantId); // Update UI after confirmation
                });
            }
        })
        .catch(error => {
            console.error('Error rejecting application:', error);
        });
    }

    function removeApplicantFromList(applicantId) {
        const applicantButton = document.querySelector(`.applicant-toggle[data-id='${applicantId}']`);
        if (applicantButton) {
            applicantButton.closest('.applicants-name').remove();
        }
    }

    //profile page button toggle
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

    document.getElementById('new-message-icon').addEventListener('click', function() {
        // Display a modal or dropdown with the list of users (admins and volunteers)
        var myModal = new bootstrap.Modal(document.getElementById('userSelectModal'));
        myModal.show();
        const selectedUserId = prompt('Enter the user ID to chat with'); // Example prompt for simplicity
        const selectedUserType = prompt('Enter the user type (admin or volunteer)'); // Prompt to choose the user type

        if (selectedUserId && selectedUserType) {
            window.location.href = `/admin/messages/new?user_id=${selectedUserId}&user_type=${selectedUserType}`;
        } else {
            alert('User ID and type are required.');
        }
    });
    
});
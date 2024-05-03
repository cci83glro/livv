function assignUserToBooking(bookingId, userId) {
    // Data to be sent in the request body
    var data = {
        booking_id: bookingId,
        user_id: userId
    };

    var formData = new FormData();
    formData.append('booking_id', bookingId);
    formData.append('user_id', userId);
    
    fetch('assign_user.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text(); // Assuming the script returns a text response
    })
    .then(data => {
        console.log('Assignment successful:', data);
        // Add code to display success message or perform other actions
        reloadBookings();
    })
    .catch(error => {
        console.error('Error assigning user:', error);
        // Add code to display error message or handle the error
    });
}

// Function to assign user to a booking
function assignUser(bookingId) {
    var userDropdown = document.getElementById('user-dropdown-' + bookingId);
    var selectedUserId = userDropdown.value;
    if (selectedUserId && selectedUserId > 0) {
        assignUserToBooking(bookingId, selectedUserId);
    }
}

// Function to unassign user from a booking
function unassignUser(bookingId) {
    
    // Data to be sent in the request body
    var data = {
        booking_id: bookingId
    };

    // Send the POST request
    var formData = new FormData();
    formData.append('booking_id', bookingId);
    
    fetch('unassign_user.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text(); // Assuming the script returns a text response
    })
    .then(data => {
        console.log('Unassignment successful:', data);
        // Add code to display success message or perform other actions
        reloadBookings();
    })
    .catch(error => {
        console.error('Error unassigning user:', error);
        // Add code to display error message or handle the error
    });
}

function reloadBookings() {
    var tbody = document.getElementById('bookingsBody');
    loadBookings(tbody.getAttribute("data-active-page"));
}

// Function to load bookings from backend with pagination
function loadBookings(page) {
    var recordsPerPage = 4; // Adjust as needed
        
    //var url = `retrieve_bookings.php?page=${page}&records_per_page=${recordsPerPage}`;
    var url = `retrieve_bookings.php`;
    
    fetch(url)
    .then(response => response.json())
    .then(data => {
        var employees = Array.from(document.getElementById('employee').options);

        var container = document.getElementById('bookings-container');
        container.innerHTML = ''; // Clear existing data
        container.setAttribute("data-active-page", page);
        
        var totalCount = data.length;
        var lowerBound = (page-1) * recordsPerPage;
        var upperBound = Math.min(lowerBound + recordsPerPage, totalCount);

        // Populate table with retrieved bookings
        data.slice(lowerBound, upperBound)
            .forEach(booking => {
            //var row = document.createElement('tr');
            var assignAction = `<button onclick="unassignUser(${booking.booking_id})">Unassign</button>`;
            if (!booking.assigned_user_id) {
                assignAction = `<select id="user-dropdown-${booking.booking_id}">`;
                employees.forEach(e => {
                    assignAction += `<option value="${e.value}">${e.text}</option>`;
                });
                assignAction += `</select>`;
                assignAction += `<button onclick="assignUser(${booking.booking_id})">Assign</button>`;
            }
            container.innerHTML += `
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse-${booking.booking_id}" aria-expanded="false"
                        aria-controls="collapse-${booking.booking_id}">
                        ${booking.place + booking.date}
                    </button>
                </h2>
                <div id="collapse-${booking.booking_id}" class="accordion-collapse collapse show"
                    data-bs-parent="#bookings-container">
                    <div class="accordion-body">
                        <p>${booking.place}</p>
                        <p>${booking.date}</p>
                        <p>${booking.time_value}</p>
                        <p>${booking.hours}</p>
                        <p>${booking.shift_name}</p>
                        <p>${booking.qualification_name}</p>
                        <p>${booking.user_name}</p>
                        <p><button onclick="deleteBooking(${booking.booking_id})">Delete</button></p>
                        <p>${assignAction}</p>    
                    </div>
                </div>
            </div>`;
        });
        
        // Generate pagination links
        var paginationDiv = document.getElementById('pagination');
        paginationDiv.innerHTML = ''; // Clear existing pagination links
        var totalPages = Math.ceil(totalCount / recordsPerPage);
        for (var i = 1; i <= totalPages; i++) {
            var link = document.createElement('a');
            link.href = '#';
            link.textContent = i;
            link.onclick = function() {
                loadBookings(this.textContent); // Load bookings for clicked page
            };
            paginationDiv.appendChild(link);
        }
    })
    .catch(error => console.error('Error:', error));
}
// JavaScript code for interacting with backend scripts and updating frontend dynamically
// Function to delete a booking
function deleteBooking(bookingId) {
    var confirmation = confirm("Are you sure you want to delete this booking?");
    if (confirmation) {
        var formData = new FormData();
        formData.append('booking_id', bookingId);
        
        fetch('delete_booking.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data); // Show response message
            // Reload the page after deletion
            reloadBookings();
        })
        .catch(error => console.error('Error:', error));
    }
}

// Load bookings when the page loads
document.addEventListener('DOMContentLoaded', function() {
    loadBookings(1);
});
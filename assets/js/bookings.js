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
    var recordsPerPage = 9; // Adjust as needed
        
    //var url = `retrieve_bookings.php?page=${page}&records_per_page=${recordsPerPage}`;
    var url = `booking/retrieve_bookings.php`;

    $.get(url, function(data) {
        var employees = Array.from($('#employee option'));

        var container = $('#bookings-container');
        container.innerHTML = ''; // Clear existing data
        container.attr("data-active-page", page);
        
        var totalCount = data.length;
        var lowerBound = (page-1) * recordsPerPage;
        var upperBound = Math.min(lowerBound + recordsPerPage, totalCount);
        var innerHtml = ``;

        data.slice(lowerBound, upperBound)
            .forEach(booking => {
            //var row = document.createElement('tr');
            var assignAction = `<button onclick="unassignUser(${booking.booking_id})">Unassign</button>`;
            var assignText = 'Unassigned';
            if (!booking.assigned_user_id) {
                assignAction = `<select id="user-dropdown-${booking.booking_id}">`;
                employees.forEach(e => {
                    assignAction += `<option value="${e.value}">${e.text}</option>`;
                });
                assignAction += `</select>`;
                assignAction += `<button onclick="booking/assignUser(${booking.booking_id})">Assign</button>`;
            } else {
                assignText = 'Assigned til ' + booking.user_name;
            }
            innerHTML += `
                <div class="accordion-item">
                    <div class="accordion-header">
                        ${booking.place + " | " +  booking.date + " (kl. " + booking.time_value + " - " + booking.hours + " timer) | " +  assignText}
                    </div>
                    <div class="accordion-content">
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
                </div>`;
        });

        container.html(innerHtml);
        
        // Generate pagination links
        var paginationDiv = $('#pagination');
        paginationDiv.html(''); // Clear existing pagination links
        var totalPages = Math.ceil(totalCount / recordsPerPage);
        if (totalPages > 1) {
            for (var i = 1; i <= totalPages; i++) {
                var link = document.createElement('a');
                link.href = '#';
                link.textContent = i;
                link.onclick = function(e) {
                    e.preventDefault();
                    loadBookings(this.textContent); // Load bookings for clicked page
                };
                paginationDiv.appendChild(link);
            }
        }
    })
    .fail(function(xhr, status, error) {
        // Handle errors
        console.error('Error:', error);
      });

   //$('.accordion-content').hide();

    // Add click event listener to accordion headers
    $('.accordion-header').click(function(){
      // Toggle the accordion content
      $(this).next('.accordion-content').slideToggle();
    });
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
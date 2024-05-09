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
    var bookingsContainer = $('#bookings-container');
    loadBookings(bookingsContainer.attr("data-active-page"));
}

// Function to load bookings from backend with pagination
function loadBookings(page) {
    var recordsPerPage = 3; // Adjust as needed
        
    //var url = `retrieve_bookings.php?page=${page}&records_per_page=${recordsPerPage}`;
    var url = `booking/retrieve_bookings.php`;

    $.getJSON(url, function(data) {
        var employees = Array.from($('#employee option'));

        var container = $('#bookings-container');
        container.html(''); // Clear existing data
        container.attr("data-active-page", page);
        
        var totalCount = data.length;
        var lowerBound = (page-1) * recordsPerPage;
        if (totalCount === lowerBound) {
            page -= 1;
            lowerBound = (page-1) * recordsPerPage;
        }
        var upperBound = Math.min(lowerBound + recordsPerPage, totalCount);        

        data.slice(lowerBound, upperBound).forEach(function(booking) {
            var assignAction = `<button onclick="unassignUser(${booking.booking_id})">Unassign</button>`;
            var assignText = 'Unassigned';
            if (!booking.assigned_user_id) {
                assignAction = `<select id="user-dropdown-${booking.booking_id}">`;
                employees.forEach(e => {
                    assignAction += `<option value="${e.value}">${e.text}</option>`;
                });
                assignAction += `</select>`;
                assignAction += `<button class="action-button-reverted" onclick="booking/assignUser(${booking.booking_id})">Assign</button>`;
            } else {
                assignText = 'Assigned til ' + booking.user_name;
            }
            var element = `
                <div class="accordion-item">
                    <div class="accordion-item-header bg-secondary-color primary-color">
                        ${booking.place + " | " +  booking.date + " (kl. " + booking.time_value + " - " + booking.hours + " timer) | " +  assignText}
                    </div>
                    <div class="accordion-item-content">
                        <p>Sted: ${booking.place}</p>
                        <p>Dato: ${booking.date}</p>
                        <p>Tidspunkt: ${booking.time_value}</p>
                        <p>Antal timer: ${booking.hours}</p>
                        <p>Stilling: ${booking.shift_name}</p>
                        <p>Uddannelse: ${booking.qualification_name}</p>
                        <p>${booking.user_name}${assignAction}</p>
                        <div class="accordion-item-content-actions">
                            <button class="delete-booking-button cancel-button" onclick="deleteBooking(${booking.booking_id})">Slet</button>
                            <button class="edit-booking-button action-button" onclick="editBooking(${booking.booking_id})">Rediger</button>
                        </div>
                    </div>
                </div>`;
            container.append(element);
        });

        $('.accordion-item-header').click(function(){
            // Toggle the accordion content
            $(this).next('.accordion-item-content').slideToggle();
        });
        
        // Generate pagination links
        var paginationDiv = $('#pagination');
        paginationDiv.html(''); // Clear existing pagination links
        var totalPages = Math.ceil(totalCount / recordsPerPage);
        if (totalPages > 1) {
            var paginationHtml = '';
            for (var i = 1; i <= totalPages; i++) {
                var active = '';
                if (page === i) {
                    active = 'active';
                }

                paginationHtml += '<span class="page-link ' + active + '" data-page="' + i + '"> ' + i + '</span>';
            }
          
            paginationDiv.append(paginationHtml);

            $('.page-link').click(function(){
                var page = parseInt($(this).data('page'));
                currentPage = page;
                loadBookings(currentPage);
            });
        }
    })
    .fail(function(xhr, status, error) {
        // Handle errors
        console.error('Error:', error);
      });
}
// JavaScript code for interacting with backend scripts and updating frontend dynamically
// Function to delete a booking
function deleteBooking(bookingId) {
    var confirmation = confirm("Slet bookingen?");
    if (confirmation) {
        var formData = new FormData();
        formData.append('booking_id', bookingId);
        
        fetch('booking/delete_booking.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            //alert(data); // Show response message
            // Reload the page after deletion
            reloadBookings();
        })
        .catch(error => console.error('Error:', error));
    }
}

function showAddBookingForm() {
    $('#add-booking-section').fadeIn("slow");
}

function hideAddBookingForm() {
    $('#add-booking-section').fadeOut("slow");
}

function resetAddBookingForm(form) {
    form[0].reset();
    form.removeClass('was-validated');
}

function addBooking() {

    var form = $('#bookingForm');

    if (form.isValid())
    {
        var formData = form.serialize();
        
        $.post('booking/create_booking.php', formData, function(response){
            if (response == 'success') {                
                hideAddBookingForm();
                resetAddBookingForm(form);
                reloadBookings();
            } else {
                const errtoast = new bootstrap.Toast($('.error_msg')[0]);
                errtoast.show();
            }
        });
    }
    form.addClass('was-validated');
}

function cancelBooking() {

//    if (confirm("Er du sikker på, du vil fortryde?")) {
        hideAddBookingForm();
        resetAddBookingForm($('#bookingForm'));
  //  }
}

// Load bookings when the page loads
document.addEventListener('DOMContentLoaded', function() {
    //hideAddBookingForm();
    loadBookings(1);
    
    $('#add-booking-submit-button').click(addBooking);
    $('#add-booking-cancel-button').click(cancelBooking);
});
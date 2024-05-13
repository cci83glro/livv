function assignUserToBooking(bookingId, userId) {
    // Data to be sent in the request body
    var data = {
        booking_id: bookingId,
        user_id: userId
    };

    var formData = new FormData();
    formData.append('booking_id', bookingId);
    formData.append('user_id', userId);
    
    fetch('booking/assign_user.php', {
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
    
    fetch('booking/unassign_user.php', {
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
    var recordsPerPage = 10; // Adjust as needed
        
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
            var assignAction = `<br/><button class="save no-margin unassign-booking" onclick="unassignUser(${booking.booking_id})">Unassign</button>`;
            var assignText = 'Unassigned';
            if (!booking.assigned_user_id) {
                assignAction = `<br/><select class="assign-users-select form-control" id="user-dropdown-${booking.booking_id}">`;
                employees.forEach(e => {
                    assignAction += `<option value="${e.value}">${e.text}</option>`;
                });
                assignAction += `</select>`;
                assignAction += `<button class="save" onclick="assignUser(${booking.booking_id})">Assign</button>`;
            } else {
                assignText = '<span>Assigned til:</span> ' + booking.user_name;
            }
            var element = `
                <div class="accordion-item">
                    <div class="accordion-item-header bg-secondary-color primary-color">
                        ${booking.place + " | " +  booking.date + " (kl. " + booking.time_value + " - " + booking.hours + " timer) | " +  assignText}
                        <span class="indicator">+</span>
                    </div>
                    <div class="accordion-item-content" data-id="${booking.booking_id}">
                        <p class="place" data-value="${booking.place}"><span>Sted:</span> ${booking.place}</p>
                        <p class="date" data-value="${booking.date}"><span>Dato:</span> ${booking.date}</p>
                        <p class="time" data-value="${booking.time_id}"><span>Tidspunkt:</span> kl. ${booking.time_value}</p>
                        <p class="hours" data-value="${booking.hours}"><span>Antal timer:</span> ${booking.hours}</p>
                        <p class="shift" data-value="${booking.shift_id}"><span>Stilling:</span> ${booking.shift_name}</p>
                        <p class="qualification" data-value="${booking.qualification_id}"><span>Uddannelse:</span> ${booking.qualification_name}</p>
                        <p class="form-actions">${assignText}${assignAction}</p>
                        <div class="form-actions">
                            <div class="buttons-wrapper">
                                <button class="cancel" onclick="deleteBooking(${booking.booking_id})">Slet</button>
                                <button class="save" onclick="editBooking(${booking.booking_id})">Rediger</button>
                            </div>
                        </div>
                    </div>
                </div>`;
            container.append(element);
        });

        $('.accordion-item-header').click(function(){
            $(this).next('.accordion-item-content').slideToggle();
            var element = $(this).find('.indicator')[0];
            var newContent = element.textContent === "+" ? "-" : "+";
            $(element).text(newContent);
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
    $($('#add-booking-submit-button')[0]).html('Tilføj');
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

function editBooking(id) {
    var item = $('.accordion-item-content[data-id='+id+']');
    if (!item) return;

    var place = $($(item).children('.place')[0]).attr('data-value');
    var date = $($(item).children('.date')[0]).attr('data-value');
    var time = $($(item).children('.time')[0]).attr('data-value');
    var hours = $($(item).children('.hours')[0]).attr('data-value');
    var shift = $($(item).children('.shift')[0]).attr('data-value');
    var qualification = $($(item).children('.qualification')[0]).attr('data-value');
    
    var form = $('#bookingForm');
    resetAddBookingForm(form);
    showAddBookingForm();

    $($(form).find('#place')[0]).val(place);
    $($(form).find('#date')[0]).val(date);
    $($(form).find('#time')[0]).val(time);
    $($(form).find('#hours')[0]).val(hours);
    $($(form).find('#shift')[0]).val(shift);
    $($(form).find('#qualification')[0]).val(qualification);
    $($(form).find('#id')[0]).val(id);
    $($('#add-booking-submit-button')[0]).html('Opdater');

    $([document.documentElement, document.body]).animate({
        scrollTop: $("#add-booking-section").offset().top - 50
    }, 500);
}


// Load bookings when the page loads
document.addEventListener('DOMContentLoaded', function() {
    //hideAddBookingForm();
    loadBookings(1);
    
    $('#add-booking-submit-button').click(addBooking);
    $('#add-booking-cancel-button').click(cancelBooking);
});
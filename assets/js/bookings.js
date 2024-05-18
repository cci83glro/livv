var bi = $($("p#bi")[0]).text();
var bp = $($("p#bp")[0]).text();

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

function assignToMe(bookingId) {
    assignUserToBooking(bookingId, bi);
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

function changeBookingStatus(bookingId, newStatus) {

    // Send the POST request
    var formData = new FormData();
    formData.append('booking_id', bookingId);
    formData.append('new_status_id', newStatus);
    
    fetch('booking/change-booking-status.php', {
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

function getAssignData(booking, employees) {
    var assignHtml = ``;
    var assignText = '';
    if (bp == 2 || bp == 3) {
        var assignAction = `<br/><button class="cancel no-margin mt-05" onclick="unassignUser(${booking.booking_id})">Unassign</button>`;
        var assignText = 'Unassigned';
        if (!booking.assigned_user_id) {
            if (bp == 2) {
                assignAction = `<br/><select class="assign-users-select form-control" id="user-dropdown-${booking.booking_id}">`;
                employees.forEach(e => {
                    assignAction += `<option value="${e.value}">${e.text}</option>`;
                });
                assignAction += `</select>`;
                assignAction += `<br/><button class="save no-margin mt-05" onclick="assignUser(${booking.booking_id})">Assign</button>`;
            } else if (bp == 3) {
                assignAction = `<br/><button class="save no-margin mt-05 w-50" onclick="assignToMe(${booking.booking_id})">Assign til mig</button>`;
            }            
        } else {
            assignText = booking.user_name;
        }    

        if (booking.status_id == 20) {
            assignAction = ``;
        }
        assignHtml = `<p class="form-actions"><span>Vikar:</span> ${assignText}${assignAction}</p>`;
    }

    return {
        assignText: assignText,
        assignHtml: assignHtml,
    }
}

function getStatusData(booking) {
    var statusHtml = ``;
    var statusText = '';
    if (bp == 1 || bp == 2 || bp == 3) {
        var statusAction = ``;
        if (booking.status_id == 10) {
            statusText = 'Oprettet';
            if (bp == 2 && booking.assigned_user_id) {
                statusAction = `<br/><button class="save w-75 no-margin change-booking-status" onclick="changeBookingStatus(${booking.booking_id}, 20)">Marker som afsluttet</button>`;
            }
        } else if (booking.status_id == 20) {
            statusText = 'Afsluttet';
        }

        statusHtml = `<p class="form-actions"><span>Tilstand:</span> ${statusText}${statusAction}</p>`;
    }

    return {
        statusText: statusText,
        statusHtml: statusHtml,
    }
}

function getFormActions(booking) {
    formActionHtml = ``;
    if (booking.status_id != 20 && (bp == 1 || bp == 2)) {
        formActionHtml = `<div class="form-actions">
            <div class="buttons-wrapper">
                <button class="cancel" onclick="deleteBooking(${booking.booking_id})">Slet</button>
                <button class="save" onclick="editBooking(${booking.booking_id})">Rediger</button>
            </div>
        </div>`;
    }

    return formActionHtml;
}

function getBookingHtml(booking, assignText, assignHtml, statusText, statusHtml, formActionsHtml) {
    var bookingHeader = booking.place + " | " +  booking.date + " (kl. " + booking.time_value + " - " + booking.hours + " timer)";
    if (statusText.length > 0) {
        bookingHeader +=  " | Tilstand: " +  statusText;
    }
    if (assignText.length > 0) {
        bookingHeader +=  " | Vikar: " +  assignText;
    }

    var element =  `<div class="accordion-item">
                        <div class="accordion-item-header bg-secondary-color primary-color">
                            {{bookingHeader}}
                            <span class="indicator">+</span>
                        </div>
                        <div class="accordion-item-content" data-id="{{booking_id}}">
                            <p class="place" data-value="{{booking_place}}"><span>Sted:</span> {{booking_place}}</p>
                            <p class="date" data-value="{{booking_date}}"><span>Dato:</span> {{booking_date}}</p>
                            <p class="time" data-value="{{booking_time_id}}"><span>Tidspunkt:</span> kl. {{booking_time_value}}</p>
                            <p class="hours" data-value="{{booking_hours}}"><span>Antal timer:</span> {{booking_hours}}</p>
                            <p class="shift" data-value="{{booking_shift_id}}"><span>Stilling:</span> {{booking_shift_name}}</p>
                            <p class="qualification" data-value="{{booking.qualification_id}}"><span>Uddannelse:</span> {{booking_qualification_name}}</p>
                            {{assignHtml}}{{statusHtml}}{{formActionsHtml}}
                        </div>
                    </div>`;
    element = element.replace('{{bookingHeader}}', bookingHeader);
    element = element.replace('{{assignHtml}}', assignHtml);
    element = element.replace('{{statusHtml}}', statusHtml);
    element = element.replace('{{formActionsHtml}}', formActionsHtml);
    element = element.replace('{{booking_id}}', booking.booking_id);
    element = element.replace('{{booking_place}}', booking.place);
    element = element.replace('{{booking_date}}', booking.date);
    element = element.replace('{{booking_time_id}}', booking.time_id);
    element = element.replace('{{booking_time_value}}', booking.time_value);
    element = element.replace('{{booking_hours}}', booking.hours);
    element = element.replace('{{booking_shift_id}}', booking.shift_id);
    element = element.replace('{{booking_shift_name}}', booking.shift_name);
    element = element.replace('{{booking_qualification_id}}', booking.qualification_id);
    element = element.replace('{{booking_qualification_name}}', booking.qualification_name);

    return element;
}

function getSliceBoundaries(length, page, recordsPerPage) {
    var totalCount = length;
    var newPage = page;
    var lowerBound = (newPage-1) * recordsPerPage;
    if (totalCount === lowerBound) {
        newPage -= 1;
        lowerBound = (newPage-1) * recordsPerPage;
    }
    var upperBound = Math.min(lowerBound + recordsPerPage, totalCount);

    return {newPage, lowerBound, upperBound};
}

function setBookingListHtml(bookingsContainer, paginationContainer, data, page, recordsPerPage) {
    var employees = Array.from($('#employee option'));
    bookingsContainer.html(''); // Clear existing data
    bookingsContainer.attr("data-active-page", page);

    var totalCount = data.length;
    var {newPage, lowerBound, upperBound} = getSliceBoundaries(totalCount, page, recordsPerPage);

    data.slice(lowerBound, upperBound).forEach(function(booking) {
        var {statusText, statusHtml} = getStatusData(booking);
        var {assignText, assignHtml} = getAssignData(booking, employees, statusText);
        var formActionsHtml = getFormActions(booking);
        bookingsContainer.append(getBookingHtml(booking, assignText, assignHtml, statusText, statusHtml, formActionsHtml));
    });

    $('.accordion-item-header').click(function(){
        $(this).next('.accordion-item-content').slideToggle();
        var element = $(this).find('.indicator')[0];
        var newContent = element.textContent === "+" ? "-" : "+";
        $(element).text(newContent);
    });

    setPaginationHtml(paginationContainer, totalCount, recordsPerPage, newPage);
}

function setPaginationHtml(paginationDiv, totalCount, recordsPerPage, page) {
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
}

function loadBookings(page) {
    var recordsPerPage = 2;

    var url = `booking/retrieve_bookings.php`;

    $.getJSON(url, function(data) {
        setBookingListHtml($('#bookings-container'), $('#pagination'), data, page, recordsPerPage);
    })
    .fail(function(xhr, status, error) {
        console.error('Error:', error);
    });
}

function loadBookingsForWorker(page) {
    var recordsPerPage = 2;

    var url = `booking/retrieve_bookings.php?bi=` + bi;

    $.getJSON(url, function(data) {
        setBookingListHtml($('#my-bookings-container'), $('#my-bookings-pagination'), data, page, recordsPerPage);
    })
    .fail(function(xhr, status, error) {
        console.error('Error:', error);
    });
}

function loadAvailableBookings(page) {
    var recordsPerPage = 2;

    var url = `booking/retrieve_bookings.php?unassigned=1`;

    $.getJSON(url, function(data) {
        setBookingListHtml($('#available-bookings-container'), $('#available-bookings-pagination'), data, page, recordsPerPage);
    })
    .fail(function(xhr, status, error) {
        console.error('Error:', error);
    });
}

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
        formData += '&bi=' + bi;
        
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
    if (bp == 1 || bp == 2) {
        loadBookings(1);
    } else if (bp == 3) {
        loadBookingsForWorker(1);
        loadAvailableBookings(1);
    }
    
    
    $('#add-booking-submit-button').click(addBooking);
    $('#add-booking-cancel-button').click(cancelBooking);
});
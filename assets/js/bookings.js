var bi = $($("p#bi")[0]).text();
var bp = $($("p#bp")[0]).text();

function assignUserToBooking(bookingId, userId) {
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
        return response.text();
    })
    .then(data => {
        console.log('Assignment successful:', data);
        reloadBookings();
    })
    .catch(error => {
        console.error('Error assigning user:', error);
    });
}

function assignToMe(bookingId) {
    assignUserToBooking(bookingId, bi);
}

function assignUser(bookingId) {
    var userDropdown = document.getElementById('user-dropdown-' + bookingId);
    var selectedUserId = userDropdown.value;
    if (selectedUserId && selectedUserId > 0) {
        assignUserToBooking(bookingId, selectedUserId);
    }
}

function unassignUser(bookingId) {

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
        return response.text();
    })
    .then(data => {
        console.log('Unassignment successful:', data);
        reloadBookings();
    })
    .catch(error => {
        console.error('Error unassigning user:', error);
    });
}

function changeBookingStatus(bookingId, newStatus) {

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
        return response.text();
    })
    .then(data => {
        console.log('Unassignment successful:', data);
        reloadBookings();
    })
    .catch(error => {
        console.error('Error unassigning user:', error);
    });
}

function getAssignData(booking, employees) {
    var assignHtml = ``;
    var assignText = '';
    if (bp == 2 || bp == 3) {
        var assignAction = `<br/><button class="cancel no-margin w-50 mt-05" onclick="unassignUser(${booking.booking_id})">Unassign</button>`;
        var assignText = '...';
        if (!booking.assigned_user_id) {
            if (bp == 2) {
                assignAction = `<br/><select class="assign-users-select form-control dropdown" id="user-dropdown-${booking.booking_id}">`;
                employees.forEach(e => {
                    assignAction += `<option value="${e.value}">${e.text}</option>`;
                });
                assignAction += `</select>`;
                assignAction += `<button class="save w-50 mt-05" onclick="assignUser(${booking.booking_id})">Assign</button>`;
            } else if (bp == 3) {
                assignAction = `<br/><button class="save no-margin mt-05 w-50" onclick="assignToMe(${booking.booking_id})">Assign til mig</button>`;
            }            
        } else {
            assignText = booking.user_name;
        }    

        if (booking.status_id == 5 || booking.status_id == 20) {
            assignAction = ``;
        }
        assignHtml = `<p class="form-actions mt-0""><span>Vikar - </span> ${assignText}${assignAction}</p>`;
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
        if (booking.status_id == 5) {
            statusText = 'Inaktiv';
            if (bp == 2) {
                statusAction = `<br/><button class="save w-50 no-margin change-booking-status mt-05" onclick="changeBookingStatus(${booking.booking_id}, 10)">Aktiver</button>`;
            }
        }if (booking.status_id == 10) {
            statusText = 'Oprettet';
            if (bp == 2 && booking.assigned_user_id) {
                statusAction = `<br/><button class="save w-50 no-margin change-booking-status mt-05" onclick="changeBookingStatus(${booking.booking_id}, 20)">Marker som afsluttet</button>`;
            }
        } else if (booking.status_id == 20) {
            statusText = 'Afsluttet';
        }

        statusHtml = `<p class="form-actions mt-0"><span>Tilstand - </span> ${statusText}${statusAction}</p>`;
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
    var bookingHeader = "ID: " + booking.booking_id + " | " + booking.district_name + " (" +  booking.place + ") | " +  booking.date + " (kl. " + booking.time_value + " - " + booking.hours + " timer)";
    if (statusText.length > 0) {
        bookingHeader +=  " | Tilstand - " +  statusText;
    }
    if (assignText.length > 0) {
        bookingHeader +=  " | Vikar - " +  assignText;
    }

    var element =  `<div class="accordion-item">
                        <div class="accordion-item-header bg-secondary-color primary-color">
                            {{bookingHeader}}
                            <span class="indicator">+</span>
                        </div>
                        <div class="accordion-item-content" data-id="{{booking_id}}">
                            <p class="district" data-value="{{booking_district_id}}"><span>Kommune - </span> {{booking_district_name}}</p>
                            <p class="place" data-value="{{booking_place}}"><span>Sted - </span> {{booking_place}}</p>
                            <p class="date" data-value="{{booking_date}}"><span>Dato - </span> {{booking_date}}</p>
                            <p class="time" data-value="{{booking_time_id}}"><span>Tidspunkt - </span> kl. {{booking_time_value}}</p>
                            <p class="hours" data-value="{{booking_hours}}"><span>Antal timer - </span> {{booking_hours}}</p>
                            <p class="shift" data-value="{{booking_shift_id}}"><span>Stilling - </span> {{booking_shift_name}}</p>
                            <p class="qualification" data-value="{{booking_qualification_id}}"><span>Uddannelse - </span> {{booking_qualification_name}}</p>
                            <div class="extra-actions">
                                <div class="col-md-6">{{assignHtml}}</div>
                                <div class="col-md-6">{{statusHtml}}</div>
                            </div>
                            {{formActionsHtml}}
                        </div>
                    </div>`;
    element = element.replaceAll('{{bookingHeader}}', bookingHeader);
    element = element.replaceAll('{{assignHtml}}', assignHtml);
    element = element.replaceAll('{{statusHtml}}', statusHtml);
    element = element.replaceAll('{{formActionsHtml}}', formActionsHtml);
    element = element.replaceAll('{{booking_id}}', booking.booking_id);
    element = element.replaceAll('{{booking_district_id}}', booking.district_id);
    element = element.replaceAll('{{booking_district_name}}', booking.district_name);
    element = element.replaceAll('{{booking_place}}', booking.place);
    element = element.replaceAll('{{booking_date}}', booking.date);
    element = element.replaceAll('{{booking_time_id}}', booking.time_id);
    element = element.replaceAll('{{booking_time_value}}', booking.time_value);
    element = element.replaceAll('{{booking_hours}}', booking.hours);
    element = element.replaceAll('{{booking_shift_id}}', booking.shift_id);
    element = element.replaceAll('{{booking_shift_name}}', booking.shift_name);
    element = element.replaceAll('{{booking_qualification_id}}', booking.qualification_id);
    element = element.replaceAll('{{booking_qualification_name}}', booking.qualification_name);

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

function setBookingListHtml(bookingsContainer, paginationContainer, data, page, recordsPerPage, loadBookingsFunction, pageLinkExtraClass = '') {
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

    $('.accordion-item-header').unbind();
    $('.accordion-item-header').click(function(){
        $(this).next('.accordion-item-content').slideToggle();
        var element = $(this).find('.indicator')[0];
        var newContent = element.textContent === "+" ? "-" : "+";
        $(element).text(newContent);
    });

    setPaginationHtml(paginationContainer, lowerBound, upperBound, totalCount, recordsPerPage, newPage, loadBookingsFunction, pageLinkExtraClass);
}

function setPaginationHtml(paginationDiv, lowerBound, upperBound, totalCount, recordsPerPage, page, loadBookingsFunction, pageLinkExtraClass) {
    paginationDiv.html('');
    var paginationHtml = '';

    var totalPages = Math.ceil(totalCount / recordsPerPage);
    if (totalPages > 1) {
        
        for (var i = 1; i <= totalPages; i++) {
            var active = '';
            if (page == i) {
                active = 'active';
            }

            paginationHtml += `<span class="page-link ${pageLinkExtraClass} ${active}" data-page="${i}">${i}</span>`;
        }        
    }

    if (totalCount > 0) {
        paginationHtml += `<span class="count-info">Viser ${lowerBound + 1} - ${upperBound} ud af ${totalCount} bookings</span>`;
    } else {
        paginationHtml += `<span class="count-info">Ingen bookings</span>`;
    }
    paginationDiv.append(paginationHtml);

    var pageLinkEventClass = 'page-link';
    if (pageLinkExtraClass !== null && pageLinkExtraClass !== '') {
        pageLinkEventClass = pageLinkExtraClass;
    }
    $('.' + pageLinkEventClass).unbind();
    $('.' + pageLinkEventClass).click(function(){
        var page = parseInt($(this).data('page'));
        currentPage = page;
        loadBookingsFunction(currentPage);
    });
}

function resetBookingsFilter() {
    $('#booking-id-filter').val('');
    var activePage = $('#bookings-container').data('active-page');
    loadBookings(activePage);
}

function filterBookingsById() {
    var id = $('#booking-id-filter').val();
    var activePage = $('#bookings-container').data('active-page');

    loadBookings(activePage, id);
}

function resetEmployeeBookingsFilter() {
    $('#booking-id-filter').val('');
    var activePage = $('#my-bookings-container').data('active-page');
    loadBookingsForWorker(activePage);

    activePage = $('#available-bookings-container').data('active-page');
    loadAvailableBookings(activePage);
}

function filterEmployeeBookingsById() {
    var id = $('#booking-id-filter').val();
    var activePage = $('#my-bookings-container').data('active-page');
    loadBookingsForWorker(activePage, id);

    activePage = $('#available-bookings-container').data('active-page');
    loadAvailableBookings(activePage, id);
}

function loadBookings(page, bookingId = '') {
    var recordsPerPage = 5;

    var url = `booking/retrieve_bookings.php`;
    if (bookingId != '') {
        url += '?bookingId=' + bookingId;
    }

    $.getJSON(url, function(data) {
        setBookingListHtml($('#bookings-container'), $('#pagination'), data, page, recordsPerPage, loadBookings);
    })
    .fail(function(xhr, status, error) {
        console.error('Error:', error);
    });
}

function loadBookingsForWorker(page, bookingId = '') {
    var recordsPerPage = 5;

    var url = `booking/retrieve_bookings.php?bi=` + bi;
    if (bookingId != '') {
        url += '&bookingId=' + bookingId;
    }

    $.getJSON(url, function(data) {
        setBookingListHtml($('#my-bookings-container'), $('#my-bookings-pagination'), data, page, recordsPerPage, loadBookingsForWorker, 'page-link-my-bookings');
    })
    .fail(function(xhr, status, error) {
        console.error('Error:', error);
    });
}

function loadAvailableBookings(page, bookingId = '') {
    var recordsPerPage = 5;

    var url = `booking/retrieve_bookings.php?unassigned=1`;
    if (bookingId != '') {
        url += '&bookingId=' + bookingId;
    }

    $.getJSON(url, function(data) {
        setBookingListHtml($('#available-bookings-container'), $('#available-bookings-pagination'), data, page, recordsPerPage, loadAvailableBookings, 'page-link-available-bookings');
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

    var district = '';
    if (bp == 2) {
        district = $($(item).children('.district')[0]).attr('data-value');
    }
    var place = $($(item).children('.place')[0]).attr('data-value');
    var date = $($(item).children('.date')[0]).attr('data-value');
    var time = $($(item).children('.time')[0]).attr('data-value');
    var hours = $($(item).children('.hours')[0]).attr('data-value');
    var shift = $($(item).children('.shift')[0]).attr('data-value');
    var qualification = $($(item).children('.qualification')[0]).attr('data-value');
    
    var form = $('#bookingForm');
    resetAddBookingForm(form);
    showAddBookingForm();

    if (bp == 2) {
        $($(form).find('#district')[0]).val(district);
    }
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

    //$('.accordion-item-content[style.display=block]').slideToggle();

    $('.accordion-item-content').animate({
        scrollTop: $("#add-booking-section").offset().top - 50
    }, 500);
}

function reloadBookings() {
    if (bp == 1 || bp == 2) {
        loadBookings($('#bookings-container').attr("data-active-page"));
    } else if (bp == 3) {
        loadBookingsForWorker($('#my-bookings-container').attr("data-active-page"));
        loadAvailableBookings($('#available-bookings-container').attr("data-active-page"));
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if (bp == 1 || bp == 2) {
        loadBookings(1);
    } else if (bp == 3) {
        loadBookingsForWorker(1);
        loadAvailableBookings(1);
    }
    
    
    $('#add-booking-submit-button').click(addBooking);
    $('#add-booking-cancel-button').click(cancelBooking);
});
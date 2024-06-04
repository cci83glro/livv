var bi = $($("p#bi")[0]).text();
var bp = $($("p#bp")[0]).text();
const recordsPerPage = 10;
var loader = $('.page-loader');

function assignUserToBooking(bookingId, userId) {

    $(loader).show();
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
    })
    .finally(e => {
        $(loader).hide();
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

    $(loader).show();
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
    })
    .finally(p => {
        $(loader).hide();
    });
}

function changeBookingStatus(bookingId, newStatus, email = '') {

    var formData = new FormData();
    formData.append('booking_id', bookingId);
    formData.append('new_status_id', newStatus);
    formData.append('email', email);
    
    $(loader).show();
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
    })
    .finally(e => {
        $(loader).hide();
    });
}

function getAssignData(booking, employees) {
    var assignHtml = ``;
    var assignText = '';
    if (bp == 2 || bp == 3) {
        var assignAction = `<br/><button class="cancel unassign-user" onclick="unassignUser(${booking.booking_id})">Unassign</button>`;
        var assignText = '...';
        if (!booking.assigned_user_id) {
            if (bp == 2) {
                assignAction = `<br/><select class="assign-users-select form-control dropdown" id="user-dropdown-${booking.booking_id}">`;
                employees.forEach(e => {
                    assignAction += `<option value="${e.value}">${e.text}</option>`;
                });
                assignAction += `</select>`;
                assignAction += `<br/><button class="save assign-user" onclick="assignUser(${booking.booking_id})">Assign</button>`;
            } else if (bp == 3) {
                assignAction = `<br/><button class="save assign-to-me" onclick="assignToMe(${booking.booking_id})">Assign til mig</button>`;
            }            
        } else {
            assignText = booking.user_name;
        }    

        if (booking.status_id == 5 || booking.status_id == 20) {
            assignAction = ``;
        }
        assignHtml = `<div class="action-wrapper"><p class="form-actions""><span>Vikar - </span> ${assignText}${assignAction}</p></div>`;
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
                statusAction = `<br/><button class="save change-booking-status" onclick="changeBookingStatus(${booking.booking_id}, 10, '${booking.created_by_email}')">Aktiver</button>`;
            }
        }if (booking.status_id == 10) {
            statusText = 'Oprettet';
            if (bp == 2 && booking.assigned_user_id) {
                statusAction = `<br/><button class="save change-booking-status" onclick="changeBookingStatus(${booking.booking_id}, 20, '${booking.created_by_email}')">Marker som afsluttet</button>`;
            }
        } else if (booking.status_id == 20) {
            statusText = 'Afsluttet';
        }

        statusHtml = `<div class="action-wrapper"><p class="form-actions mt-0"><span>Tilstand - </span> ${statusText}${statusAction}</p></div>`;
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

    var element =  `<div class="accordion-item booking">
                        <div class="accordion-item-header bg-secondary-color primary-color">
                            <p>{{bookingHeader}}</p>
                            <span class="indicator">+</span>
                        </div>
                        <div class="accordion-item-content" data-id="{{booking_id}}">
                            <p class="district" data-value="{{booking_district_id}}"><span>Kommune</span> {{booking_district_name}}</p>
                            <p class="createdBy" data-value="{{booking_createdBy}}"><span>Oprettet af</span> {{booking_createdBy}}</p>
                            <p class="user-data" data-value="{{booking_user_data}}"><span>Bruger</span> {{booking_user_data}}</p>
                            <p class="place" data-value="{{booking_place}}"><span>Sted</span> {{booking_place}}</p>
                            <p class="date" data-value="{{booking_date}}"><span>Dato</span> {{booking_date}}</p>
                            <p class="time" data-value="{{booking_time_id}}"><span>Tidspunkt</span> kl. {{booking_time_value}}</p>
                            <p class="hours" data-value="{{booking_hours}}"><span>Antal timer</span> {{booking_hours}}</p>
                            <p class="shift" data-value="{{booking_shift_id}}"><span>Stilling</span> {{booking_shift_name}}</p>
                            <p class="qualification" data-value="{{booking_qualification_id}}"><span>Uddannelse</span> {{booking_qualification_name}}</p>
                            <div class="extra-actions">
                               {{assignHtml}}
                               {{statusHtml}}
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
    element = element.replaceAll('{{booking_createdBy}}', booking.createdBy);
    element = element.replaceAll('{{booking_user_data}}', booking.created_by_name + ' (' + booking.created_by_email + ')');    
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

function getSliceBoundaries(length, page) {
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

function setBookingListHtml(bookingsContainer, paginationContainer, data, page, loadBookingsFunction, pageLinkExtraClass = '') {
    var employees = Array.from($('#employee option'));
    bookingsContainer.html('');
    bookingsContainer.attr("data-active-page", page);

    var totalCount = data.length;
    var {newPage, lowerBound, upperBound} = getSliceBoundaries(totalCount, page);

    var month = '';
    var totalHours = 0;
    data.slice(lowerBound, upperBound).forEach(function(booking) {
        totalHours += booking.hours;
        var currentDate = new Date(booking.date);
        var currentMonth = currentDate.toLocaleString('da-dk', { month: 'long' });
        if (currentMonth != month) {
            month = currentMonth;
            bookingsContainer.append(`<p2 class="bookings-subsection-header">` + month + ` ` + currentDate.getFullYear() +`</p2>`);
        }
        var {statusText, statusHtml} = getStatusData(booking);
        var {assignText, assignHtml} = getAssignData(booking, employees, statusText);
        var formActionsHtml = getFormActions(booking);
        bookingsContainer.append(getBookingHtml(booking, assignText, assignHtml, statusText, statusHtml, formActionsHtml));
    });
    $('#total-hours').text('Antal timer: ' + totalHours);

    $('.accordion-item-header').unbind();
    $('.accordion-item-header').click(function(){
        $(this).next('.accordion-item-content').slideToggle();
        var element = $(this).find('.indicator')[0];
        var newContent = element.textContent === "+" ? "-" : "+";
        $(element).text(newContent);
    });

    setPaginationHtml(paginationContainer, lowerBound, upperBound, totalCount, newPage, loadBookingsFunction, pageLinkExtraClass);
}

function setPaginationHtml(paginationDiv, lowerBound, upperBound, totalCount, page, loadBookingsFunction, pageLinkExtraClass) {
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
    $('#search-text').val('');
    $('#filter-district-id').val('');
    $('#filter-employee-id').val('');
    $('#filter-qualification-id').val('');
    $('#filter-shift-id').val('');
    $('#filter-from-date-value').val('');
    $('#filter-to-date-value').val('');
    $('#filter-from-time-id').val('');
    $('#filter-to-time-id').val('');
    
    var activePage = $('#bookings-container').data('active-page');
    loadBookings(activePage);
}

function filterBookings() {
    
    var activePage = $('#bookings-container').data('active-page');
    loadBookings(activePage, id);
}

function resetEmployeeBookingsFilter() {
    $('#search-text').val('');
    var activePage = $('#my-bookings-container').data('active-page');
    loadBookingsForWorker(activePage);

    activePage = $('#available-bookings-container').data('active-page');
    loadAvailableBookings(activePage);
}

function filterEmployeeBookingsById() {    
    var activePage = $('#my-bookings-container').data('active-page');
    loadBookingsForWorker(activePage);

    activePage = $('#available-bookings-container').data('active-page');
    loadAvailableBookings(activePage);
}

function loadBookings(page) {

    var url = `booking/retrieve_bookings.php`;

    var bookingsType = $('#filter-bookings-type').val();
    url += '?bookingsType=' + bookingsType;
    if($('#active-bookings-type').text() !== bookingsType) {
        $('#active-bookings-type').text(bookingsType);
        page = 1;
    }

    var districtId = '';
    if ($('#filter-district-id').length) {
        districtId = $('#filter-district-id').val();
        if (districtId != '') {
            url += '&districtId=' + districtId;
        }
        if($('#active-district-id').text() !== districtId) {
            $('#active-district-id').text(districtId);
            page = 1;
        }
    }

    var employeeId = '';
    if ($('#filter-employee-id').length) {
        employeeId = $('#filter-employee-id').val();
        if (employeeId != '') {
            url += '&employeeId=' + employeeId;
        }
        if($('#active-employee-id').text() !== employeeId) {
            $('#active-employee-id').text(employeeId);
            page = 1;
        }
    }

    var qualificationId = $('#filter-qualification-id').val();
    if (qualificationId != '') {
        url += '&qualificationId=' + qualificationId;
    }
    if($('#active-qualification-id').text() !== qualificationId) {
        $('#active-qualification-id').text(qualificationId);
        page = 1;
    }

    var shiftId = $('#filter-shift-id').val();
    if (shiftId != '') {
        url += '&shiftId=' + shiftId;
    }
    if($('#active-shift-id').text() !== shiftId) {
        $('#active-shift-id').text(shiftId);
        page = 1;
    }

    // if ($('#filter-from-date').attr('type') === 'date' && $('#filter-from-date').val()) {
    //     $('#filter-from-date').data('actual-date', $('#filter-from-date').val());
    // }
    var fromDate = $('#filter-from-date-value').attr('data-actual-date');
    if (fromDate != '') {
        url += '&fromDate=' + fromDate;
    }
    if($('#active-from-date').text() !== fromDate) {
        $('#active-from-date').text(fromDate);
        page = 1;
    }

    // if ($('#filter-tp-date').attr('type') === 'date' && $('#filter-to-date').val()) {
    //     $('#filter-to-date').data('actual-date', $('#filter-to-date').val());
    // }
    var toDate = $('#filter-to-date-value').attr('data-actual-date');
    if (toDate != '') {
        url += '&toDate=' + toDate;
    }
    if($('#active-to-date').text() !== toDate) {
        $('#active-to-date').text(toDate);
        page = 1;
    }

    var searchText = $('#search-text').val();
    if (searchText != '') {
        url += '&searchText=' + searchText;
    }
    if ($('#active-search-text').text() !== searchText) {
        page = 1;
        $('#active-search-text').text(searchText);
    }

    var fromTime = $('#filter-from-time-id').val();
    if (fromTime != '') {
        url += '&fromTime=' + fromTime;
    }
    if($('#active-from-time-id').text() !== fromTime) {
        $('#active-from-time-id').text(fromTime);
        page = 1;
    }
    
    var toTime = $('#filter-to-time-id').val();
    if (toTime != '') {
        url += '&toTime=' + toTime;
    }
    if($('#active-to-time-id').text() !== toTime) {
        $('#active-to-time-id').text(toTime);
        page = 1;
    }

    $.getJSON(url, function(data) {
        setBookingListHtml($('#bookings-container'), $('#pagination'), data, page, loadBookings);
    })
    .fail(function(xhr, status, error) {
        console.error('Error:', error);
    });
}

function loadBookingsForWorker(page) {

    var url = `booking/retrieve_bookings.php?bi=` + bi;

    var searchText = $('#search-text').val();
    if (searchText != '') {
        url += '&searchText=' + searchText;
        page = 1;
    }

    $.getJSON(url, function(data) {
        setBookingListHtml($('#my-bookings-container'), $('#my-bookings-pagination'), data, page, loadBookingsForWorker, 'page-link-my-bookings');
    })
    .fail(function(xhr, status, error) {
        console.error('Error:', error);
    });
}

function loadAvailableBookings(page) {

    var url = `booking/retrieve_bookings.php?unassigned=1`;
    var searchText = $('#search-text').val();
    if (searchText != '') {
        url += '&searchText=' + searchText;
        page = 1;
    }

    $.getJSON(url, function(data) {
        setBookingListHtml($('#available-bookings-container'), $('#available-bookings-pagination'), data, page, loadAvailableBookings, 'page-link-available-bookings');
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
    var createdBy = $($(item).children('.createdBy')[0]).attr('data-value');
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
    $($(form).find('#createdBy')[0]).val(createdBy);
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

function onDisplayTypeChange() {
    var bookingsType = $('#filter-bookings-type').val();
    if (bookingsType=='reports') {
        $('#free-text-search').hide();
        $('#filter-qualifications').hide();
        $('#filter-shifts').hide();
        $('#filter-from-start-time').hide();
        $('#filter-to-start-time').hide();
        //$('#total-hours').show();
        resetBookingsFilter();
    } else {
        $('#free-text-search').show();
        $('#filter-qualifications').show();
        $('#filter-shifts').show();
        $('#filter-from-start-time').show();
        $('#filter-to-start-time').show();
        //$('#total-hours').hide();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    try {
        $(loader).show();
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }
        const inputValue = getQueryParam('searchText');
        if (inputValue) {
            $('#search-text').val(inputValue);
        }

        if (bp == 1 || bp == 2) {
            loadBookings(1);
        } else if (bp == 3) {
            loadBookingsForWorker(1);
            loadAvailableBookings(1);
        }
        
        $('#add-booking-submit-button').click(addBooking);
        $('#add-booking-cancel-button').click(cancelBooking);

        $('#search-text').on( "keydown", function( event ) {
            if(event.which==13){
                if (bp == 1 || bp == 2) {
                    filterBookings();
                } else if (bp == 3) {
                    filterEmployeeBookingsById();
                }
            }
        });
    }
    catch(e) {}
    finally {
        $(loader).hide();
    }
});
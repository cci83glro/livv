<?php

    require_once '../master.php';

    $db = DB::getInstance();
    
    $query = $db->query("SELECT time_id, time_value FROM Times");
    $times = $query->results();

    $query = $db->query("SELECT shift_id, shift_name FROM Shifts");
    $shifts = $query->results();

    $query = $db->query("SELECT qualification_id, qualification_name FROM Qualifications");
    $qualifications = $query->results();

    $query = $db->query("SELECT u.id, CONCAT(u.fname, ' ', u.lname) as name
        FROM livv.users u INNER JOIN user_permission_matches up ON u.id=up.user_id
        WHERE up.permission_id = 3");
    $employees = $query->results();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking</title>
<style>
/* Basic CSS for styling */
table {
    width: 100%;
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid black;
}

th, td {
    padding: 8px;
    text-align: left;
}

button {
    padding: 5px 10px;
    cursor: pointer;
}

</style>
</head>
<body>

<h2>Booking System</h2>

<!-- Booking Creation Form -->
<h3>Create Booking</h3>
<form id="bookingForm" method="post" action="create_booking.php" data-user-id="<?php echo $user_id; ?>">
    <label for="place">Place:</label>
    <input type="text" id="place" name="place" required><br><br>
    
    <label for="date">Date:</label>
    <input type="date" id="date" name="date" required><br><br>

    <label for="time">Time:</label>
    <select id="time" name="time" required>
        <option value="">Select time</option>
        <?php 
            foreach($times as $time){
                echo "<option value='$time->time_id'>$time->time_value</option>"; 
            }            
        ?>
    </select>
    
    <label for="hours">Number of Hours:</label>
    <input type="number" id="hours" name="hours" required><br><br>
    
    <label for="shift">Shift:</label>
    <select id="shift" name="shift" required>
        <option value="">Select Shift</option>
        <?php 
            foreach($shifts as $shift){
                echo "<option value='$shift->shift_id'>$shift->shift_name</option>"; 
            }            
        ?>
    </select><br><br>
    
    <label for="qualification">Qualification:</label>
    <select id="qualification" name="qualification" required>
        <option value="">Select Qualification</option>
        <?php 
            foreach($qualifications as $qualification){
                echo "<option value='$qualification->qualification_id'>$qualification->qualification_name</option>"; 
            }            
        ?>
    </select><br><br>

    <select id="employee" name="employee" style="display:none">
        <option value="">Select employee</option>
        <?php 
            foreach($employees as $employee){
                echo "<option value='$employee->id'>$employee->name</option>"; 
            }            
        ?>
    </select>
    
    <button type="submit">Create Booking</button>
</form>

<!-- Display Bookings -->
<h3>Bookings</h3>
<table id="bookingsTable">
    <thead>
        <tr>
            <th>Place</th>
            <th>Date</th>
            <th>Time</th>
            <th>Hours</th>
            <th>Shift</th>
            <th>Qualification</th>
            <th>Assigned user</th>
            <th>Action</th>            
        </tr>
    </thead>
    <tbody id="bookingsBody" data-active-page="1">
    </tbody>
</table>

<!-- Pagination -->
<div id="pagination">
    
</div>

<script>

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

        var tbody = document.getElementById('bookingsBody');
        tbody.innerHTML = ''; // Clear existing data
        tbody.setAttribute("data-active-page", page);
        
        var totalCount = data.length;
        var lowerBound = (page-1) * recordsPerPage;
        var upperBound = Math.min(lowerBound + recordsPerPage, totalCount);

        // Populate table with retrieved bookings
        data.slice(lowerBound, upperBound)
            .forEach(booking => {
            var row = document.createElement('tr');
            var assignAction = `<button onclick="unassignUser(${booking.booking_id})">Unassign</button>`;
            if (!booking.assigned_user_id) {
                assignAction = `<select id="user-dropdown-${booking.booking_id}">`;
                employees.forEach(e => {
                    assignAction += `<option value="${e.value}">${e.text}</option>`;
                });
                assignAction += `</select>`;
                assignAction += `<button onclick="assignUser(${booking.booking_id})">Assign</button>`;
            }
            row.innerHTML = `
                <td>${booking.place}</td>
                <td>${booking.date}</td>
                <td>${booking.time_value}</td>
                <td>${booking.hours}</td>
                <td>${booking.shift_name}</td>
                <td>${booking.qualification_name}</td>
                <td>${booking.user_name}</td>
                <td><button onclick="deleteBooking(${booking.booking_id})">Delete</button></td>
                <td>${assignAction}</td>
            `;
            tbody.appendChild(row);
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
</script>

</body>
</html>
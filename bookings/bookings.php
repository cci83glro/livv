<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking System</title>
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
<form id="bookingForm" method="post" action="create_booking.php">
    <label for="place">Place:</label>
    <input type="text" id="place" name="place" required><br><br>
    
    <label for="date">Date:</label>
    <input type="date" id="date" name="date" required><br><br>
    
    <label for="hours">Number of Hours:</label>
    <input type="number" id="hours" name="hours" required><br><br>
    
    <label for="shift">Shift:</label>
    <select id="shift" name="shift" required>
        <option value="">Select Shift</option>
        <option value="1">dagvagt</option>
        <option value="2">aftenvagt</option>
        <option value="3">natvagt</option>
        <option value="4">fastvagt</option>
    </select><br><br>
    
    <label for="qualification">Qualification:</label>
    <select id="qualification" name="qualification" required>
        <option value="">Select Qualification</option>
        <option value="1">SSA</option>
        <option value="2">SSH</option>
        <option value="3">sygeplejerske</option>
        <option value="4">pædagog</option>
    </select><br><br>
    
    <button type="submit">Create Booking</button>
</form>

<!-- Display Bookings -->
<h3>Bookings</h3>
<table id="bookingsTable">
    <thead>
        <tr>
            <th>Place</th>
            <th>Date</th>
            <th>Hours</th>
            <th>Shift</th>
            <th>Qualification</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="bookingsBody">
        <?php
        // Include PHP script to retrieve bookings
        require_once "retrieve_bookings.php";
        ?>
    </tbody>
</table>

<!-- Pagination -->
<div id="pagination">
    <?php
    // Include PHP script to generate pagination links
    require_once "pagination.php";
    ?>
</div>

<script>
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
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>

</body>
</html>
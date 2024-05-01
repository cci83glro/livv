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

<script src="../assets/js/bookings.js"></script>

</body>
</html>
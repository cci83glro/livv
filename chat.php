
<?php
	$public = false;
	$permissions = [2, 3];
	$pageTitle = 'Chat';

	include_once __DIR__."/master-pages/header.php";

	if ($user_id == 0) {
		die("Invalid token");
	}
	
    $db = dbo::getInstance();

    $times = $db->query("SELECT time_id, time_value FROM Times")->fetchAll();
    $shifts = $db->query("SELECT shift_id, shift_name FROM Shifts")->fetchAll();
    $qualifications = $db->query("SELECT qualification_id, qualification_name FROM Qualifications")->fetchAll();
	$employees = $db->query("SELECT id, CONCAT(fname, ' ', lname) as name FROM livv.users WHERE permissions = 3")->fetchAll();
	$districts = $db->query("SELECT * FROM districts ORDER BY district_name ASC")->fetchAll();
?>

<div class="chat-wrapper">
	<div id="message-box"></div>
	<div class="user-panel">
		<input type="text" name="message" id="message" placeholder="Din besked her ..." maxlength="100" />
		<button id="send-message">Send</button>
	</div>
</div>

<?php include_once __DIR__."/master-pages/footer.php"?>
<script src="assets/js/chat.js"></script>

</body>
</html>

<?php
	$public = false;
	$permissions = [2, 3];
	$pageTitle = 'Chat';

	include_once __DIR__."/master-pages/header.php";

	if ($user_id == 0) {
		die("Invalid token");
	}

	$hideFooter = true;

?>

<div id="chat-wrapper">
	<div id="chat-messages-container"></div>
	<div class="user-panel">
		<textarea type="text" name="message" id="message" rows="5" placeholder="Din besked her ..." maxlength="500"></textarea>
		<button id="send-message">Send</button>
	</div>
</div>

<?php include_once __DIR__."/master-pages/footer.php"?>
<script src="assets/js/chat.js"></script>

</body>
</html>
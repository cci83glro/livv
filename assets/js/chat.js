var msgBox = $('#message-box');
var wsUri = "ws://localhost:9000/demo/server.php"; 	
// websocket = new WebSocket(wsUri); 

// websocket.onopen = function(ev) { // connection is open 
//     msgBox.append('<div class="system_msg" style="color:#bbbbbb">Welcome to my "Demo WebSocket Chat box"!</div>'); //notify user
// }
// // Message received from server
// websocket.onmessage = function(ev) {
//     var response 		= JSON.parse(ev.data); //PHP sends Json data
    
//     var res_type 		= response.type; //message type
//     var user_message 	= response.message; //message text
//     var user_name 		= response.name; //user name
//     var user_color 		= response.color; //color

//     switch(res_type){
//         case 'usermsg':
//             msgBox.append('<div><span class="user_name" style="color:' + user_color + '">' + user_name + '</span> : <span class="user_message">' + user_message + '</span></div>');
//             break;
//         case 'system':
//             msgBox.append('<div style="color:#bbbbbb">' + user_message + '</div>');
//             break;
//     }
//     msgBox[0].scrollTop = msgBox[0].scrollHeight; //scroll message 

// };

// websocket.onerror	= function(ev){ msgBox.append('<div class="system_error">Error Occurred - ' + ev.data + '</div>'); }; 
// websocket.onclose 	= function(ev){ msgBox.append('<div class="system_msg">Connection Closed</div>'); }; 

//Message send button
$('#send-message').click(function(){
    send_message();
});

//User hits enter key 
$( "#message" ).on( "keydown", function( event ) {
    if(event.which==13){
        send_message();
    }
});

//Send message
function send_message(){
    var message_input = $('#message'); //user message text
    var name_input = $('#name'); //user name
    
    if(message_input.val() == ""){ //empty name?
        alert("Enter your Name please!");
        return;
    }
    if(message_input.val() == ""){ //emtpy message?
        alert("Enter Some message Please!");
        return;
    }

    //prepare json data
    var msg = {
        message: message_input.val(),
        name: name_input.val(),
        color : '<?php echo $colors[$color_pick]; ?>'
    };
    //convert and send data to server
    websocket.send(JSON.stringify(msg));	
    message_input.val(''); //reset message input
}

function setBookingListHtml(container) {
    data.forEach(function(booking) {
        var {statusText, statusHtml} = getStatusData(booking);
        var {assignText, assignHtml} = getAssignData(booking, employees, statusText);
        var formActionsHtml = getFormActions(booking);
        bookingsContainer.append(getBookingHtml(booking, assignText, assignHtml, statusText, statusHtml, formActionsHtml));
    });
}

function loadChatMessages() {
    var url = `chat/retrieve_chat_messages.php`;

    $.getJSON(url, function(data) {
        setBookingListHtml($('#chat-messages-container'));

    })
    .fail(function(xhr, status, error) {
        console.error('Error:', error);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    try {
        $(loader).show();
        loadChatMessages();
    }
    catch(e) {}
    finally {
        $(loader).hide();
    }
});
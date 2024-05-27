var bi = $($("p#bi")[0]).text();
var loader = $('.page-loader');
var msgBox = $('#message-box');

//var wsUri = "ws://localhost:9000/demo/server.php"; 	
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


//Send message
function addChatMessage(){
    var message_input = $('#message'); //user message text
    
    if(message_input.val() == ""){ //emtpy message?
        //alert("Indtast venligst en besked!");
        return;
    }

    var formData = new FormData();
    formData.append('message', message_input.val());

    $(loader).show();
    fetch('chat/add-chat-message.php', {
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
        message_input.val('');
        loadChatMessages();
    })
    .catch(error => {
        console.error('Error adding chat message:', error);
    })
    .finally(e => {
        $(loader).hide();
    });
    
}

function getChatMessageHtml(chatMessage) {
    var element =  `<div class="chat-message {{extraClasses}}">
                        <span class="message-info">{{messageInfo}}</span><p>{{messageText}}</p>
                    </div>`;

    var extraClasses = "";
    if (chatMessage.user_id == bi) {
        extraClasses += "me ";
    }

    element = element.replaceAll('{{extraClasses}}', extraClasses);
    element = element.replaceAll('{{messageInfo}}', '[' + chatMessage.author + ' - ' + chatMessage.date_created + ']');
    element = element.replaceAll('{{messageText}}', chatMessage.message);
    return element;
}

function setChatMessagesListHtml(container, data) {
    container.html('');
    if (!data) return;
    data.forEach(function(chatMessage) {
        container.append(getChatMessageHtml(chatMessage));
    });
}

function loadChatMessages() {
    var url = `chat/retrieve-chat-messages.php`;

    $.getJSON(url, function(data) {
        setChatMessagesListHtml($('#chat-messages-container'), data);
    })
    .fail(function(xhr, status, error) {
        console.error('Error:', error);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    try {
        $(loader).show();
        loadChatMessages();

        //Message send button
        $('#send-message').click(function(){
            addChatMessage();
        });

        //User hits enter key 
        $( "#message" ).on( "keydown", function( event ) {
            if(event.which==13){
                addChatMessage();
            }
        });
    }
    catch(e) {}
    finally {
        $(loader).hide();
    }
});
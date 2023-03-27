//Initialize chat box target message user globally for use in functions
var selectedUserName = "";

$(document).ready(function () {
    //Close chat box and chats on page load
    $('#messageClose').hide()
    $('#userChat').hide()


    //If the page loads with the box checked connect the socket to receive messages through
    if($('#useSocketCheck').is(":checked")) {
        connectMessageSocket();
    }
    else {
        connectAjaxMessagePolling(inboxUserNames);
    }

    //Open/Close chat box
    $('#chatBoxIcon').on('click', function () {
        $('#chatBoxContainer').toggleClass('active');
    });

    //Close user chats and remove chats
    $('#messageClose').on('click', function () {
        selectedUserName = "";

        //Messages are closed poll inbox display messages
        setAjaxPollUser("");

        $('#messageClose').hide()
        $('#headerText').text('Chats');
        $('#userInbox').show();
        $('#userChat').hide()
        $('#userChatContainer').empty();
    });

    //On connection type changed open or close the socket and update indicator
    $('#useSocketCheck').change(function() {
        if($('#useSocketCheck').is(":checked")){
            connectMessageSocket();
            disconnectAjaxMessagePolling();
            $('#sliderLbl').text('Websocket');
        }
        else {
            connectAjaxMessagePolling();
            disconnectMessageSocket();
            $('#sliderLbl').text('Ajax');
        }
    })

    //Open chats between primary user and the user selected
    jQuery("[id=userMessageContainer]").on('click', function () {
        selectedUserName = $(this).find("p").first().text();
        
        //Check slider value to use either web socket or ajax call to get message between two users
        if($('#useSocketCheck').is(":checked")) {
            getChatMessagesSocket(selectedUserName);
        }
        else {
            //Get chat messages for Ajax will return the whole array of objects run through the items and append
            getChatMessagesAjax(selectedUserName).then(
                function(messages) {
                    if(messages.length) {
                        for (x in messages) {
                            appendMessage(messages[x]['sender'], messages[x]['receiver'], messages[x]['message'], messages[x]['isRead']);
                        }
                    }
                }
            );

            //After opening user messages mark receiving messages as read
            readChatMessagesAjax(selectedUserName);
        }

        //Set the ajax poll to begin querying the messages between session and target user
        setAjaxPollUser(selectedUserName);

        $('#messageClose').show()
        $('#headerText').text(selectedUserName);
        $('#userChat').show()
        $('#userInbox').hide();

        //Call update preview after chats are opened to mark read on display
        updateChatPreview(selectedUserName);
    });

    //Send chat to target on send button clicked or enter key pressed if message isn't blank
    $('#messageSend').on('click', function () {
        if($('#userMsgInput').val() != "") {

            //Check slider to send message as Socket or as Ajax
            if($('#useSocketCheck').is(":checked")) {
                sendMessageSocket(selectedUserName, $('#userMsgInput').val());
            }
            else {
                sendMessageAjax(selectedUserName, $('#userMsgInput').val());
            }

            //Append the user sent message to the chat box and reset text value
            appendMessage('self', selectedUserName, $('#userMsgInput').val(), 0);
            $('#userMsgInput').val('');
        }
    });
    $('#userMsgInput').keydown(function (e) {
        if (e.keyCode == 13 && !e.shiftKey) {
            e.preventDefault();

            if($('#userMsgInput').val() != "") {
                //Check slider to send message as Socket or as Ajax
                if($('#useSocketCheck').is(":checked")) {
                    sendMessageSocket(selectedUserName, $('#userMsgInput').val());
                }
                else {
                    sendMessageAjax(selectedUserName, $('#userMsgInput').val());
                }

                //Append the user sent message to the chat box and reset text value
                appendMessage('self', selectedUserName, $('#userMsgInput').val(), 0);
                $('#userMsgInput').val('');
            }
        }
    });
});

//Update Chat and call update preview when a message is sent or received through socket
function appendMessage(sender, receiver, message, isRead) {
    //If the message is read then it's already been displayed as the preview and we don't have update it if not it's a newly sent message to the user
    if (!isRead) {
        updateChatPreview(sender, receiver, message, isRead);
    }

    //Only update the user chats on message received if the chats are open and if the users are the same, on chats open every message will be sent to populate chat box. If chats are open when sent, mark as read.
    if($('#userChat').is(":visible")) {
        if(sender == 'self' || sender == getCookie('userName')) {
            $('#userChatContainer').append('<p class="text-right">' + message + '</p>');
        }
        else {
            //Sent chats will be in the current chat box and only receiving chats need to be validated if it's the current chat box. Validate that the sender is the selected user
            if (sender == selectedUserName) {
                $('#userChatContainer').append('<p>' + sender + ': ' + message + '</p>');

                //Check slider to call read messages as Socket or as Ajax
                if($('#useSocketCheck').is(":checked")) {
                    readChatMessagesSocket(sender);
                }
                else {
                    readChatMessagesAjax(sender);
                }
            }
        } 

        //Scroll the chat box down so new messages are displayed at the bottom
        $('#userChatContainer').scrollTop($('#userChatContainer').prop("scrollHeight"));
    }
}

//Update chat box user preview message (if blank leave message the same change highlight)
function updateChatPreview(sender, receiver, message = '', isRead) {
    //The name of the opposing user as they appear in the chat box
    var targetUserName;
    //Had to add a check for isSelf for ajax, we always call messages to check for new ones and we encounter our own messages in the call. If they are unread by sender it shouldn't highlight for us.
    var isSelf = false; 

    //Check if if self and set the targetUser to update chat elements and supply isSelf
    if(sender == 'self' || sender == getCookie('userName')) {
        targetUserName = receiver;
        isSelf = true;
    }
    else {
        targetUserName = sender;
        isSelf = false;
    }
        
    //Inbox preview messages (latest message between two users)
    var inboxMessageElements = $('#userInbox').find("p");
    //The element of the opposing user that contains chat preview
    var targetUserElement;
    //Chat preview p element
    var targetMessageElement;

    //Get Index of the User element which is followed by the message element
    inboxMessageElements.each(function(index) {
        if ($(this).text() == targetUserName) {
            targetUserElement = $('#userInbox').find("p:eq(" + (index) + ")");
            targetMessageElement = $('#userInbox').find("p:eq(" + (index + 1) + ")");
        }
    });

    //Set the inbox preview message to the new message and prepend the user in the chat box if it is not already set on page load and is not blank if isSelf is true the message may be us retrieving old messages do not prepend
    if(targetMessageElement.html() != message && message != '' && !isSelf) {
        targetMessageElement.html(message);
        $('#userInbox').prepend(targetMessageElement.parent().parent());
    } 
    //If sender == 'self' then it was just sent and we can prepend it
    else if (sender == 'self'){
        targetMessageElement.html(message);
        $('#userInbox').prepend(targetMessageElement.parent().parent());
    }

    //If chat is not open when message is sent highlight messages as unread (If self or is already read don't highlight)
    if(!$('#userChat').is(":visible") && !isSelf && !isRead) {
        targetUserElement.attr('id', 'messageUserUnread');
        targetMessageElement.attr('id', 'messagePreviewUnread'); 
    }
    //Else if user chat is open and the sender isn't equal to the user we're chatting and is isn't a message sent by us then highlight the message for the other user chats
    else if ($('#userChat').is(":visible") && sender != selectedUserName && !isSelf) {
        targetUserElement.attr('id', 'messageUserUnread');
        targetMessageElement.attr('id', 'messagePreviewUnread'); 
    }
    //Else mark as read, likely because we have the chat for that user open 
    else {
        targetUserElement.attr('id', 'messageUserRead');
        targetMessageElement.attr('id', 'messagePreviewRead');
    }
}
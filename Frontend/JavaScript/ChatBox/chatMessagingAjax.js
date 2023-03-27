var ajaxPoll;
var ajaxPollUser = "";

//Poll the API every 5 seconds for the latest message from each user (indicated by not giving the api a target user)
function connectAjaxMessagePolling() {
    ajaxPoll = setInterval(async function() {
        //Poll Display Messages (Last sent of every target user)
        if (ajaxPollUser == "") {
            await getChatMessagesAjax().then(
                async function(messages) {
                    //Check that the response is not empty
                    if(messages.length) {
                        for(const x of messages) {
                            updateChatPreview(x['sender'], x['receiver'], x['message'], x['isRead']);
                        }
                    }
                }
            );
        }
        //Poll user messages (Every message between session user and target user) append every one that's not read or sent by us (A new message to us)
        else { 
            await getChatMessagesAjax(ajaxPollUser).then(
                async function(messages) {
                    //Check that the response is not empty
                    if(messages.length) {
                        for(const x of messages) {
                            if(x['sender'] != getCookie('userName') && !x['isRead'])
                            {
                                appendMessage(x['sender'], x['receiver'], x['message'], x['isRead']);
                            }
                        }
                    }
                }
            );
        }
    }, 5000);
}

//Providing a username will poll all the chat messages between the sessiong and target users, settting the string empty tells the poll to get display messages instead
function setAjaxPollUser(userName) 
{
    ajaxPollUser = userName;
}

//When switched to socket disconnect the message polling
function disconnectAjaxMessagePolling() {
    //Ensure the poll interval is active and if so clear it
    if(ajaxPoll) {
        clearInterval(ajaxPoll);
    }
}

//GET Request
//Get chat messages from api. no target user will provide all message pairs last sent chat and providing a targetUser will get all chats between the message pair and mark them as read
async function getChatMessagesAjax(targetUsername = "") { 
    var reqString = "https://www.jramosportfolio.com/chatMessageAPI";

    //Build the string if we have a provided user name or not, use cookies to get our current users user ID which acts as our api key for calls
    if(getCookie('userID').length) {
        if (targetUsername.length) {
            reqString = reqString + "?user1=" + getCookie('userID') + "&user2=" + targetUsername;
        }
        else {
            reqString = reqString + "?user1=" + getCookie('userID');
        }


        const res = await fetch( reqString, { 
            method: "GET",
            headers: { "Content-Type": "application/json;charset=UTF-8" }, 
        });

        return await res.json();
    }
    //If there's an error provide a blank value nulling iteration over messages 
    else {
        console.log("Invalid Credentials");
        return {};
    }
}

//POST Request
//Send message to the specified user with provided message
async function sendMessageAjax(targetUsername, message) {
    var reqString = "https://www.jramosportfolio.com/chatMessageAPI";
    
    if(getCookie('userID').length) {
        reqString = reqString + "?user1=" + getCookie('userID') + "&user2=" + targetUsername;

        const res = await fetch( reqString, { 
            method: "POST",
            headers: { "Content-Type": "application/json" }, 
            body: JSON.stringify({ message: message }),
        });
    }
    //If there's an error provide a blank value nulling iteration over messages 
    else {
        console.log("Invalid Credentials");
        return {};
    }
}

//PUT Request
//Updates the message_read element in the db for received messages by a given username
async function readChatMessagesAjax(targetUsername) {
    var reqString = "https://www.jramosportfolio.com/chatMessageAPI";
    
    if(getCookie('userID').length) {
        reqString = reqString + "?user1=" + getCookie('userID') + "&user2=" + targetUsername;

        const res = await fetch( reqString, { 
            method: "PUT",
            headers: { "Content-Type": "application/json;charset=UTF-8" },
        });
    }
    //If there's an error provide a blank value nulling iteration over messages 
    else {
        console.log("Invalid Credentials");
        return {};
    }
}
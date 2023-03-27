//Initialize a tracker variable for tracking if the display currently wants a socket connection or not (this serves the purpose of fixing a disconnection and reconencting the socket messaging for the user)
var socketInUse = false;

//Initialize socet on the users id stored in cookie
const socket = io.connect('https://www.JRamosPortfolio.com', {
    reconnection: true,
    reconnectionDelay: 1000,
    reconnectionDelayMax : 5000,
    reconnectionAttempts: Infinity
});

//Check if connection has been interuptted and if so it will autmatically try to recconnect
socket.on("disconnect", (reason) => {});

//When socket is disconnected it'll recconnect once it has a sustainable connection with the server, however the server would have disconnected the user from messaging so on recconnect reconnect to messaging if using WebSocket connection
socket.io.on("reconnect", () => {
    if(socketInUse) {
        connectMessageSocket();
    }
});

//Message is received through websocket
socket.on('chat-message', data => {
    receiveMessageSocket(data);
});

//Connect user to socket users to begin receiving messages through socket
function connectMessageSocket() {
    if(socket.active) {
        userID = getCookie('userID');
        socket.emit('new-user', userID);
        socketInUse = true;
    }
}

//Disconnect from socket users to stop receiving messages through socket
function disconnectMessageSocket() {
    if(socket.active) {
        socket.emit('remove-socket-user');
        socketInUse = false;
    }
}

//Process received messages
function receiveMessageSocket(messageData) {
    appendMessage(messageData['sender'], messageData['receiver'], messageData['message'], messageData['isRead']);
}

//Send a message to username with message
function sendMessageSocket(targetUsername, message) {
    if(socket.active) {
        socket.emit('send-chat-message', targetUsername, message);
    }
}

//Get chats between 2 users response comes through as a chat message responses --
function getChatMessagesSocket(targetUsername) {
    if(socket.active) {
        socket.emit('get-chat-messages', targetUsername)
        readChatMessagesSocket(targetUsername);
    }
}

//Mark chats as read from user
function readChatMessagesSocket(targetUsername) {
    if(socket.active) {
        socket.emit('read-chat-messages', targetUsername);
    }
}
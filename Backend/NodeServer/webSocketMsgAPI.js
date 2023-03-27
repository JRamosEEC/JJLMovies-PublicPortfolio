//Messaging Socket WebService ---------
const MsgDB = require('./DB/messageQueries.js');

const MsgSocketPort = 3001;

//Begin socket service on specified port
const MsgSocketAPI = require('socket.io')(MsgSocketPort);
console.log(`listening on port ${MsgSocketPort}`);

const users = {}

//Key : Value | SocketID : userID - to send message to the receiver we need to get their socket connection with their userID
function getKeyByValue(object, value) {
  return Object.keys(object).find(key => object[key] === value)
}

//Socket Connection
MsgSocketAPI.on('connection', socket => {
  //Add user to the active users list (this enables messages sending and receiving)
  socket.on('new-user', user => {
    //Ensure the message connection isn't already established
    if (!users[socket.id]) {
      users[socket.id] = user;
    }
  })
  //Remove user from active users to disable message system
  socket.on('remove-socket-user', () => {
    if (users[socket.id]) {
      delete users[socket.id];
    }
  })

  //Request to get all messages between two users with supplied name and socket userID return messages with names
  socket.on('get-chat-messages', async (userName) => {
    //Users can only supply their targetUser's name for added security by keeping id's private so use the supplied name and get the ID of the targer user
    const userData = await MsgDB.getUserID(userName);
    const userID = userData[0]['user_id'];

    //Use the id's to get the messages stored between the message pair
    const results = await MsgDB.getChatMessages(users[socket.id], userID);

    //For Loop has to be const to run syncronously - In this for loop Build response and send each message to the client individually
    for (const x of results) {
      //Return the names of the users for response
      const senderData = await MsgDB.getUserName(x['sender_id']);
      const senderName = senderData[0]['username'];

      const receiverData = await MsgDB.getUserName(x['receiver_id']);
      const receiverName = receiverData[0]['username'];

      const message = x['message_content'];
      const messageRead = x['message_read'];

      //Emit each response as a chat-message which the client will then use to append to chat box
      socket.emit( 'chat-message' , { sender: senderName, receiver: receiverName, message: message, isRead: messageRead} );
    }
  })

  //Request to store a new chat message and attempt to send it to socket user
  socket.on('send-chat-message', async (recipientName, message) => {
    const receiverData = await MsgDB.getUserID(recipientName);
    const receiverID = receiverData[0]['user_id'];
    
    //Store the message between the message pair
    await MsgDB.storeMessage(users[socket.id], receiverID, message);

    const senderData = await MsgDB.getUserName(users[socket.id]);
    const senderName = senderData[0]['username'];

    //Get the socket connection of the targetUser by using the receiverID (If it exists)
    const receiverSocket = getKeyByValue(users, receiverID);

    //Ensure the response is sent to the message receiver
    socket.to(receiverSocket).emit( 'chat-message', { sender: senderName, receiver: recipientName, message: message, isRead: 0 } );
  })

  //Set received messages as read between a message pair, called on opening the messages
  socket.on('read-chat-messages', async (userName) => {
    const userData = await MsgDB.getUserID(userName);
    const userID = userData[0]['user_id'];

    MsgDB.readChatMessages(users[socket.id], userID);
  })
  
  //This is called on disconnect of user socket which can be done intentionally or by connectivity issues. Remove them from active socket users to be re-enabled again after conenction re-established
  socket.on('disconnect', () => {
    if (users[socket.id]) {
      delete users[socket.id];
    }
  })
});

//On connection error alert my server
MsgSocketAPI.on("connect_error", (err) => {
  console.log(`connect_error due to ${err.message}`);
});
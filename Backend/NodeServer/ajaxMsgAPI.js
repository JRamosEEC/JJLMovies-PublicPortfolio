//Messaging API (For Ajax polling) ---------
const MsgDB = require('./DB/messageQueries.js');

const express = require('express');
const cors = require('cors');

const bodyParser = require('body-parser');


//Messaging REST API
const MsgRESTAPI = express();
const MsgRESTPort = 3002;

MsgRESTAPI.use(cors({ origin: ['https://www.jramosportfolio.com'] }));

MsgRESTAPI.use(bodyParser.urlencoded({ extended: false }))
MsgRESTAPI.use(bodyParser.json())

//GET request for user messages (providing one user id get's last message from all that users message pairs, providing two id's returns all the message from that one message pair)
MsgRESTAPI.get('/chatMessageAPI', async (req, res) => {
  var results;
  var response = [];

  //Check for id two if there is get chat messages between the message pair
  if (req.query.user2) {
    const userData = await MsgDB.getUserID(req.query.user2);
    const userID = userData[0]['user_id'];

    results = await MsgDB.getChatMessages(req.query.user1, userID);
  }
  //If no second ID return last message from every message pair with user
  else {
    results = await MsgDB.getLastMessages(req.query.user1);
  }

  //For Loop has to be const to run syncronously - In this for loop construct the response as an array of each individual message retrieved from the chats
  for (const x of results) {
    const senderData = await MsgDB.getUserName(x['sender_id']);
    const senderName = senderData[0]['username'];

    const receiverData = await MsgDB.getUserName(x['receiver_id']);
    const receiverName = receiverData[0]['username'];

    const message = x['message_content'];
    const messageRead = x['message_read'];

    //Build an array of Objects to be returned in json
    response.push( { sender: senderName, receiver: receiverName, message: message, isRead: messageRead} );
  }

  //Respond to the request with the assembled array converted to json
  res.json(response);
});

//PUT Request reads chat message between the supplied user id's
MsgRESTAPI.put('/chatMessageAPI', async (req, res) => {
  var results;

  //The second supplied id is the receiver's name which will be converted to an id
  const userData = await MsgDB.getUserID(req.query.user2);
  const userID = userData[0]['user_id'];

  //Use the first user is the primary users id, use the two the read the chat messages of the message pair (only the ones sent by opposing user received by calling user)
  MsgDB.readChatMessages(req.query.user1, userID);

  //Finish request by returning status 200
  res.sendStatus(200);
});

//POST Request will use the two supplied user id's to store the message (request body) between the message pair
MsgRESTAPI.post('/chatMessageAPI', async (req, res) => {
  const receiverData = await MsgDB.getUserID(req.query.user2);
  const receiverID = receiverData[0]['user_id'];
  
  //Use the two id's to store the supplied message
  await MsgDB.storeMessage(req.query.user1, receiverID, req.body['message']);

  //Respond with status 200
  res.sendStatus(200);
});

//Start the API server listening on set port
MsgRESTAPI.listen(MsgRESTPort, () => console.log(`listening on port ${MsgRESTPort}`));

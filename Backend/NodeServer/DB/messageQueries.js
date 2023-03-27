//Require DB Connection
const DB = require('./dbConnection.js');

//Get ID from supplied username from messager and get username of sender to return to the receiver
function getUserID(userName) {
  return new Promise((resolve, reject) => {
    DB.db_connection.getConnection((err, connection) => {
      if (err) {console.log(err)}

      connection.query("SELECT user_id FROM user_accounts WHERE username = ?", [userName], (error, results) => {
        if (error)
          throw reject(error);
        resolve(results);
        connection.release(err => { if (err) console.log(err) });
      }); 
    });
  });
}
function getUserName(userID) {
  return new Promise((resolve, reject) => {
    DB.db_connection.getConnection((err, connection) => {
      if (err) {console.log(err)}
   
      connection.query("SELECT username FROM user_accounts WHERE user_id = ?", [userID], (error, results) => {
        if (error)
          throw reject(error);
        resolve(results);
        connection.release(err => { if (err) console.log(err) });
      }); 
    });
  });
}
//--

//Retrieves the messages between the messaging userID and the receiving userName and marks them as read
function getChatMessages(user1, user2) {
  return new Promise((resolve, reject) => {
    DB.db_connection.getConnection((err, connection) => {
      if (err) {console.log(err)}
    
      connection.query("SELECT sender_id, receiver_id, message_content, sent_date, message_read FROM user_messages WHERE pair_id = SUBSTRING(HEX(AES_ENCRYPT(LEAST( ? , ? ), GREATEST( ? , ? ))), 1, 36) ORDER BY sent_date ASC", [user1, user2, user1, user2], (error, results) => {
        if (error)
          throw reject(error);
        resolve(results);
        connection.release(err => { if (err) console.log(err) });
      });
    });
  });
}

//Returns the last messages sent to a specified user (Primarily for the Ajax polling because it will need to refresh latest messges)
function getLastMessages(user) {
  return new Promise((resolve, reject) => {
    DB.db_connection.getConnection((err, connection) => {
      if (err) {console.log(err)}
    
      connection.query(
      "SELECT pair_id, sender_id, receiver_id, message_content, sent_date, message_read FROM " +
        "(SELECT pair_id, sender_id, receiver_id, message_content, sent_date, message_read " +
          "FROM user_messages " +
          "WHERE user_messages.sender_id = ? " +
          "OR user_messages.receiver_id = ? " +
        "UNION ALL " +
        "SELECT SUBSTRING(HEX(AES_ENCRYPT(LEAST(follower_id, followee_id), GREATEST(follower_id, followee_id))), 1, 36) AS pair_id, follower_id AS sender_id, followee_id AS receiver_id, ' ' AS message_content, null as sent_date, 0 AS message_read " +
          "FROM user_follow " +
          "WHERE user_follow.follower_id = ? " +
        "ORDER BY sent_date DESC " +
        ") AS chatMessages " +
      "GROUP BY pair_id", 
      [user, user, user], (error, results) => {
        if (error)
          throw reject(error);
        resolve(results);
        connection.release(err => { if (err) console.log(err) });
      });
    });
  });
}

//Set messages as read between two users (This specifically marks the receiver's as read leaving the sender's received messages unread)
function readChatMessages(user1, user2) {
  return new Promise((resolve, reject) => {
    DB.db_connection.getConnection((err, connection) => {
      if (err) {console.log(err)}
    
      connection.query("UPDATE user_messages SET message_read = 1 WHERE pair_id = SUBSTRING(HEX(AES_ENCRYPT(LEAST( ? , ? ), GREATEST( ? , ? ))), 1, 36) AND receiver_id = ?", [user1, user2, user1, user2, user1], (error, results) => {
        if (error)
          throw reject(error);
        resolve(results);
        connection.release(err => { if (err) console.log(err) });
      });
    });
  });
}

//Stores a message between sender and receiver
async function storeMessage(senderID, receiverID, message) {
  //I Encrypt my id's so they can be processed as ordered data without exposing it to the user. This function get's the last Id used and adds one to use the next distinct number.
  const msgIdResponse = await new Promise((resolve, reject) => {
    DB.db_connection.getConnection((err, connection) => {
      if (err) {console.log(err)}

      connection.query("SELECT CAST(AES_DECRYPT(UNHEX(id), '122901')+1 AS UNSIGNED) AS NUM FROM user_messages ORDER BY NUM DESC limit 0,1", (error, results) => {
        if (error)
          throw reject(error);
        resolve(results);
        connection.release(err => { if (err) console.log(err) });
      });
    });
  });
  const msgId = msgIdResponse[0]['NUM'];

  //This stores the message between the message pair and uses the next id and encrypts it
  return new Promise((resolve, reject) => {
    DB.db_connection.getConnection((err, connection) => {
      if (err) {console.log(err)}
    
      connection.query(
      "INSERT INTO user_messages " +
      "VALUES ( " +
      "HEX(AES_ENCRYPT( ? , '122901')), SUBSTRING(HEX(AES_ENCRYPT(LEAST( ? , ? ), GREATEST( ? , ? ))), 1, 36), ? , ? , CURRENT_TIMESTAMP, ? , 0)", 
      [msgId, senderID, receiverID, senderID, receiverID, senderID, receiverID, message], (error, results) => {
        if (error)
            throw reject(error);
        resolve(results);
        connection.release(err => { if (err) console.log(err) });
      });
    });
  });
}

module.exports = { getUserID, getUserName, getChatMessages, getLastMessages, readChatMessages, storeMessage}
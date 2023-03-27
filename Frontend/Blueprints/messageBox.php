<?php
    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/chatBoxQueries.php");

    //Ensure user session (logged in) before grabbing message data and instantiating chat box
    if(isset($_SESSION['user'])) {
        $chatInbox = getDisplayMessages($_SESSION['user']);
    }
?>

<?php if(isset($_SESSION['user'])) : ?>

<link rel="stylesheet" href="/Frontend/CSS/messageBox.css">
<script defer src="https://www.jramosportfolio.com/socket.io/socket.io.js"></script>
<script defer src="/Frontend/JavaScript/ChatBox/chatMessagingSocket.js"></script>
<script defer src="/Frontend/JavaScript/ChatBox/chatMessagingAjax.js"></script>
<script defer src="/Frontend/JavaScript/ChatBox/chatBoxDisplay.js"></script>

<div id="chatBoxContainer">
    <div id="chatBoxIcon"></div>

    <div id="chatHeader" class="row no-margin">
        <div class="col no-pad">
            <h2 id="headerText" class="text-left font-weight-bold no-margin">Chats</h2>
        </div>

        <div class="col no-pad">
            <div id="connectionSliderContainer" class="row no-margin justify-content-end">
                <p id="sliderLbl" class="no-margin no-padL text-right">Websocket</p>

                <label id="connectionSlider" class="switch">
                    <input id="useSocketCheck" type="checkbox" checked>
                    <span class="slider round"></span>
                </label>

                <div id="messageClose" class="text-right font-weight-bold">X</div>
            </div>
        </div>
    </div>
    
    <div id="userInbox" class="col no-pad">
        <?php foreach ($chatInbox as $row): ?>
            <?php 
                //The messages are stored in user pairs so display the opposing user by determining if we as the user sent the current message
                if($row['sender'] == $_SESSION['user']){ 
                    $userData = getUserByID($row['receiver']); 
                } 
                else { 
                    $userData = getUserByID($row['sender']); 
                }

                $senderName = $userData[0]['username'];
        
                //Each row of data contains a message pair between user and a friend display each target user and the last message sent (Ordered by sent date)
                echo '
                    <div id="userMessageContainer" class="row no-margin">
                        <div class="col-3 no-pad d-flex justify-content-center align-items-center">
                            <img id="messageProfileIcon" src="/images/profile-icon-logged-in.png" width="40px" height="40px">
                        </div>

                        <div class="col-8 no-pad">';

                //Display either highlighted unread message or a regular dimmed read message depending on sender and read status
                if ($row['isRead']) { echo '
                            <p id="messageUserRead" class="no-margin">' . $senderName . '</p>
                            <p id="messagePreviewRead" class="no-margin">' . $row['message'] . '</p>';
                }
                else if (!$row['isRead'] && $row['receiver'] == $_SESSION['user']) { echo '
                            <p id="messageUserUnread" class="no-margin">' . $senderName . '</p>
                            <p id="messagePreviewUnread" class="no-margin">' . $row['message'] . '</p>';
                }
                else { echo '
                            <p id="messageUserRead" class="no-margin">' . $senderName . '</p>
                            <p id="messagePreviewRead" class="no-margin">' . $row['message'] . '</p>';
                }
                
                echo'
                        </div>
                    </div>'; 
            ?>
        <?php endforeach; ?>   
    </div>

    <div id="userChat" class="col no-pad">
        <div id="userChatContainer"></div>

        <div id="userTextContainer" class="row">
            <div class="col mh-100 no-padR">
                <textarea id="userMsgInput" class="no-pad" name="userMsgInput" type="text" rows="1"></textarea>
            </div>

            <div class="col-1 mh-100 no-pad mr-2 d-flex align-middle">
                <img id="messageSend" src="/images/sendMsg-icon.png" width="20px" height="20px">
            </div>
        </div>
    </div>
</div>

<?php endif; ?>
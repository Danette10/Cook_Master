<?php
require_once ('init.php');
$action = htmlspecialchars($_POST['action']);
global $db;

switch ($action) {
    case 'getMessages':
        $idReceiver = htmlspecialchars($_POST['idReceiver']);
        $idSender = htmlspecialchars($_POST['idSender']);
        $selectMessages = $db->prepare('SELECT * FROM message WHERE idReceiver = :idReceiver AND idSender = :idSender OR idReceiver = :idSender AND idSender = :idReceiver ORDER BY dateSend ASC');
        $selectMessages->execute([
            'idReceiver' => $idReceiver,
            'idSender' => $idSender
        ]);
        $messages = $selectMessages->fetchAll(PDO::FETCH_ASSOC);

        if($messages){
            foreach ($messages as $message){

                $nameReceiver = $db->prepare('SELECT firstname, lastname FROM users WHERE idUser = :idUser');
                $nameReceiver->execute([
                    'idUser' => $idReceiver
                ]);
                $nameReceiver = $nameReceiver->fetch(PDO::FETCH_ASSOC);
                if ($message['idSender'] == $idSender){
                    ?>
                    <div class="messageSender">
                        <p>
                            <strong>Vous</strong><br>
                            <?= $message['message']; ?>
                        </p>
                        <p class="dateSendSender">
                            Le <?= date('d/m/Y', strtotime($message['dateSend'])); ?> à <?= date('H:i', strtotime($message['dateSend'])); ?>
                        </p>
                    </div>
                <?php }else{ ?>
                    <div class="messageReceiver">
                        <p>
                            <strong><?= $nameReceiver['firstname'] . ' ' . $nameReceiver['lastname']; ?></strong><br>
                            <?= $message['message']; ?>
                        </p>
                        <p class="dateSendReceiver">
                            Le <?= date('d/m/Y', strtotime($message['dateSend'])); ?> à <?= date('H:i', strtotime($message['dateSend'])); ?>
                        </p>
                    </div>
                    <?php
                }
            }
        }else{
            ?>
            <div class="messageReceiver">
                <p>Aucun message</p>
            </div>
            <?php
        }
        break;

    case 'sendMessage':
        $message = htmlspecialchars($_POST['message']);
        $idReceiver = htmlspecialchars($_POST['idReceiver']);
        $idSender = htmlspecialchars($_POST['idSender']);
        $insertMessage = $db->prepare('INSERT INTO message (message, status, idReceiver, idSender, dateSend) VALUES (:message, :status, :idReceiver, :idSender, NOW())');
        $insertMessage->execute([
            'message' => $message,
            'status' => 0,
            'idReceiver' => $idReceiver,
            'idSender' => $idSender
        ]);
        break;

}
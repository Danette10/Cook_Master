<?php
require_once('../init.php');
$action = htmlspecialchars($_POST['action']);
global $db;

switch($_POST['action']) {
    case 'getMessages':
        $idReceiver = htmlspecialchars($_POST['idReceiver']);
        $idSender = htmlspecialchars($_POST['idSender']);
        $selectMessages = $db->prepare('SELECT * FROM message WHERE idReceiver = :idReceiver AND idSender = :idSender OR idReceiver = :idSender AND idSender = :idReceiver ORDER BY dateSend ASC');
        $selectMessages->execute([
            'idReceiver' => $idReceiver,
            'idSender' => $idSender
        ]);
        $messages = $selectMessages->fetchAll(PDO::FETCH_ASSOC);

        $nameReceiver = $db->prepare('SELECT firstname, lastname FROM users WHERE idUser = :idUser');
        $nameReceiver->execute(['idUser' => $idReceiver]);
        $nameReceiver = $nameReceiver->fetch(PDO::FETCH_ASSOC);

        foreach ($messages as $message){
            if ($message['idSender'] == $idSender){
                ?>
                <div class="messageSender">
                    <p>
                        <strong>Vous</strong><br>
                        <?= htmlspecialchars($message['message']); ?>
                    </p>
                    <p class="dateSendSender">
                        Le <?= date('d/m/Y', strtotime($message['dateSend'])); ?> à <?= date('H:i', strtotime($message['dateSend'])); ?>
                    </p>
                </div>
            <?php }else{ ?>
                <div class="messageReceiver">
                    <p>
                        <strong><?= htmlspecialchars($nameReceiver['firstname'] . ' ' . $nameReceiver['lastname']); ?></strong><br>
                        <?= htmlspecialchars($message['message']); ?>
                    </p>
                    <p class="dateSendReceiver">
                        Le <?= date('d/m/Y', strtotime($message['dateSend'])); ?> à <?= date('H:i', strtotime($message['dateSend'])); ?>
                    </p>
                </div>
                <?php
            }
        }
        break;
}
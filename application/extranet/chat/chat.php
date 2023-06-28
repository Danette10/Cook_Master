<?php
if(isset($_SESSION['role'])){
    if($_SESSION['role'] != 2 && $_SESSION['role'] != 3 && $_SESSION['role'] != 4){
        header('Location: ' . ADDRESS_SITE);
        exit();
    }
}else{
    header('Location: ' . ADDRESS_SITE);
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Chat";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

global $db;
?>

<body>

<main>

    <h1 class="mt-3 text-center">Messagerie</h1>

    <div class="col-md-8 d-flex" style="margin: 0 auto;">
        <div class="prestaList col-md-3">
            <?php
            if($_SESSION['role'] != 4){
                $selectPresta = $db->prepare('SELECT * FROM users WHERE role = 4');
                $selectPresta->execute();
                $prestaList = $selectPresta->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <ul class="flex-column">
                <?php
                foreach ($prestaList as $presta){
                    ?>
                    <li>
                        <p onclick="openChat(<?= $presta['idUser']; ?>)" class="d-flex flex-column">
                            <?= $presta['firstname'] . ' ' . $presta['lastname']; ?>
                            <span class="typing d-none" id="isTyping_<?= $presta['idUser']; ?>">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </p>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <?php
            }else{
                $selectMessages = $db->prepare('SELECT * FROM message WHERE idReceiver = :idReceiver OR idSender = :idSender GROUP BY idReceiver, idSender');
                $selectMessages->execute([
                    'idReceiver' => $_SESSION['id'],
                    'idSender' => $_SESSION['id']
                ]);
                $messages = $selectMessages->fetchAll(PDO::FETCH_ASSOC);
                $idUsers = [];
                foreach ($messages as $message){
                    if($message['idReceiver'] != $_SESSION['id']){
                        $idUsers[] = $message['idReceiver'];
                    }else{
                        $idUsers[] = $message['idSender'];
                    }
                }
                $idUsers = array_unique($idUsers);
                ?>
                <ul class="flex-column">
                    <?php
                    foreach ($idUsers as $idUser){
                        $nameReceiver = $db->prepare('SELECT firstname, lastname FROM users WHERE idUser = :idUser');
                        $nameReceiver->execute([
                            'idUser' => $idUser
                        ]);
                        $nameReceiver = $nameReceiver->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <li>
                            <p onclick="openChat(<?= $idUser; ?>)" class="d-flex flex-column">
                                <?= $nameReceiver['firstname'] . ' ' . $nameReceiver['lastname']; ?>
                                <span class="typing d-none" id="isTyping_<?= $idUser; ?>">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </span>
                            </p>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            <?php
            }
            ?>
        </div>
        <div class="chat col-md-6 d-none">
            <div class="chatContent">
                <div class="chatContentMessages" id="chatContentMessages">
                </div>
                <div class="chatContentMessagesInput d-flex">
                    <input type="text" name="message" id="message" placeholder="Votre message" class="flex-grow-1" onkeyup="isTyping()">
                    <img src="<?= ADDRESS_IMG; ?>send.png" alt="send" id="sendMessage" width="30px" onclick="sendMessage()">
                    <input type="hidden" name="idReceiver" id="idReceiver">
                </div>
            </div>
        </div>
    </div>


</main>

<script>
    // LOCAL
    const userId = <?= $_SESSION['id']; ?>;
    const socket = new WebSocket('ws://localhost:8081');

    // PROD
    //const socket = new WebSocket('wss://cookorama.fr:9999');

    let messagesQueue = [];

    socket.onopen = function() {
        const message = {
            action: 'setUserId',
            userId: userId
        };
        socket.send(JSON.stringify(message));

        while (messagesQueue.length > 0) {
            let data = messagesQueue.shift();
            socket.send(JSON.stringify(data));
        }
    };

    socket.onmessage = function (event) {
        let data = JSON.parse(event.data);

        if(data.action === 'sendMessage'){
            openChat(data.idSender);
        } else if(data.action === 'isTyping') {
            document.getElementById('isTyping_' + data.idSender).classList.remove('d-none');
            setTimeout(function () {
                document.getElementById('isTyping_' + data.idSender).classList.add('d-none');
            }, 5000);
        }
    };


    socket.onclose = function (event) {
        console.log("onclose");
    };

    socket.onerror = function(event) {
        console.log("onerror", event);
    };

    document.getElementById('message').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

</script>

</body>

</html>
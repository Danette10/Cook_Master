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
            <ul>
                <?php
                foreach ($prestaList as $presta){
                    ?>
                    <li>
                        <p onclick="openChat(<?= $presta['idUser']; ?>)"><?= $presta['firstname'] . ' ' . $presta['lastname']; ?></p>
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
                <ul>
                    <?php
                    foreach ($idUsers as $idUser){
                        $nameReceiver = $db->prepare('SELECT firstname, lastname FROM users WHERE idUser = :idUser');
                        $nameReceiver->execute([
                            'idUser' => $idUser
                        ]);
                        $nameReceiver = $nameReceiver->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <li>
                            <p onclick="openChat(<?= $idUser; ?>)"><?= $nameReceiver['firstname'] . ' ' . $nameReceiver['lastname']; ?></p>
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
                <div class="chatContentMessages">
                </div>
                <div class="chatContentMessagesInput">
                    <input type="text" name="message" id="message" placeholder="Votre message" style="width: 100%;">
                    <input type="hidden" name="idReceiver" id="idReceiver">
                    <button type="button" onclick="sendMessage()">Envoyer</button>
                </div>
            </div>
        </div>
    </div>


</main>

<script>

    // LOCAL
    const socket = new WebSocket('ws://localhost:8081');

    // PROD
    //const socket = new WebSocket('wss://cookorama.fr:9999');

    socket.onopen = function (e) {
        console.log("Connection established!");
    };

    socket.onmessage = function (event) {
        let data = JSON.parse(event.data);

        if(data.action === 'sendMessage'){
            openChat(data.idSender);
        }
    };


    socket.onclose = function (event) {
        console.log("onclose");
    };

    socket.onerror = function(event) {
        console.log("onerror", event);
    };


</script>

</body>

</html>
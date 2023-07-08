const WebSocket = require('ws');
const https = require('https');
const fs = require('fs');
const mysql = require('mysql2');
require('dotenv').config();

// LOCAL
const db = mysql.createConnection({
    host: 'localhost',
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database : process.env.DB_NAME
});

db.connect(err => {
    if(err) {
        console.error('Error connecting to the database:', err);
        return;
    }
});

const wss = new WebSocket.Server({ port: 8081 });

wss.on('listening', function () {
    console.log('WebSocket Server started');
});

function saveMessageToDB(message) {
    db.query(
        'INSERT INTO message (message, status, idSender, idReceiver, dateSend) VALUES (?, ?, ?, ?, ?)',
        [message.message, 0, message.idSender, message.idReceiver, message.dateSend],
        function(err, results) {
            if (err) {
                console.error('Error inserting the message into the database:', err);
                return;
            }
            console.log('Message inserted into the database');
        }
    );
}

function broadcast(data, sender) {
    wss.clients.forEach(client => {
        if (client !== sender && client.readyState === WebSocket.OPEN) {
            client.send(JSON.stringify(data));
        }
    });
}

wss.on('connection', ws => {
    ws.onmessage = function(event) {
        const message = JSON.parse(event.data);
        if (message.action === 'setUserId') {
            ws.id = message.userId;
            // console.log(`Client '${ws.id}' connected`);
        } else if (message.action === 'isTyping') {
            broadcast(message, ws);
        } else if (message.action === 'sendMessage') {
            // Insert the message into the database
            saveMessageToDB(message);

            // Broadcast the message to other clients
            broadcast(message, ws);
        }
    };
});


// WebSocket server creation for PROD environment
/*
const options = {
    key: fs.readFileSync(process.env.CERTIFICAT_KEY),
    cert: fs.readFileSync(process.env.CERTIFICAT_CERT)
};

const server = https.createServer(options);
const wss = new WebSocket.Server({ server });

wss.on('listening', function () {
    //console.log('WebSocket Server started');
});

wss.on('connection', ws => {

    ws.onmessage = function(event) {
        const message = JSON.parse(event.data);
        if (message.action === 'setUserId') {
            ws.id = message.userId;
            // console.log(`Client '${ws.id}' connected`);
        } else if (message.action === 'isTyping') {
            broadcast(message, ws);
        } else if (message.action === 'sendMessage') {
            // Insert the message into the database
            saveMessageToDB(message);

            // Broadcast the message to other clients
            broadcast(message, ws);
        }
    };
});

server.listen(9999, () => {
    console.log('Server started');
});
*/

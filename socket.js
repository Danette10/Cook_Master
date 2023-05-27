const WebSocket = require('ws');
const https = require('https');
const fs = require('fs');
require('dotenv').config();

// Function to generate a random 4 digit hexadecimal number
function generateRandomHex() {
    return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
}

// Function to generate a unique ID
function getUniqueID() {
    return generateRandomHex() + generateRandomHex() + '-' + generateRandomHex();
}

// Function to handle incoming messages
function handleIncomingMessages({data}, ws, wss) {
    wss.clients.forEach(client => {
        if (client !== ws && client.readyState === WebSocket.OPEN) {
            client.send(`${data}`);
        }
    });
}

// WebSocket server creation for LOCAL environment
const wssLocal = new WebSocket.Server({ port: 8081 });
wssLocal.on('connection', ws => {
    ws.id = getUniqueID();
    ws.on('message', (data) => handleIncomingMessages(data, ws, wssLocal));
    ws.on('close', () => console.log(`Client ${ws.id} has disconnected!`));
});

// WebSocket server creation for PROD environment
/*const options = {
    key: fs.readFileSync(process.env.CERTIFICAT_KEY),
    cert: fs.readFileSync(process.env.CERTIFICAT_CERT)
};

const server = https.createServer(options);
const wssProd = new WebSocket.Server({ server });
wssProd.on('connection', ws => {
    ws.id = getUniqueID();
    ws.on('message', (data) => handleIncomingMessages(data, ws, wssProd));
});
server.listen(9999, () => console.log('HTTPS Server started'));*/

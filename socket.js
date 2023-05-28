// LOCAL

const WebSocket = require('ws');
const https = require('https');
const fs = require('fs');
require('dotenv').config();

const wss = new WebSocket.Server({ port: 8081 });


wss.on('listening', function () {
    //console.log('WebSocket Server started');
});


wss.getUniqueID = function () {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
    }
    return s4() + s4() + '-' + s4();
};

wss.on("connection", ws => {
    ws.id = wss.getUniqueID();
    //console.log(`New client connected with id: ${ws.id}`);

    ws.onmessage = ({data}) => {
        //console.log(`Client ${ws.id}: ${data}`);
        wss.clients.forEach(function each(client) {
            if (client !== ws && client.readyState === WebSocket.OPEN) {
                client.send(`${data}`);
            }
        });
    };

    ws.onclose = function() {
        //console.log(`Client ${ws.id} has disconnected!`);
    };
});

// WebSocket server creation for PROD environment
/*const options = {
    key: fs.readFileSync(process.env.CERTIFICAT_KEY),
    cert: fs.readFileSync(process.env.CERTIFICAT_CERT)
};

const server = https.createServer(options);
const wss = new WebSocket.Server({ server });

wss.getUniqueID = function () {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
    }
    return s4() + s4() + '-' + s4();
};

wss.on("connection", ws => {
    ws.id = wss.getUniqueID();

    ws.onmessage = ({data}) => {

        wss.clients.forEach(function each(client) {
            if (client !== ws && client.readyState === WebSocket.OPEN) {
                client.send(`${data}`);
            }
        });
    };

    ws.onclose = function() {

    };
});

server.listen(9999, () => {

});*/

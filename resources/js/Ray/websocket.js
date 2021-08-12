export function listenRayEvents(host, port, callback) {
    let WebSocketClient = require('websocket').w3cwebsocket;
    const client = new WebSocketClient(`ws://${host}:${port}/`);

    client.onmessage = callback
}

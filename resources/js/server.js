import {store} from "./store";
import SfdumpFunc from './Utils/dumper'
import ReconnectingWebSocket from 'reconnecting-websocket';
import EventFactory from "./EventFactory";

function createWsConnection(host, port) {
    return new ReconnectingWebSocket(`ws://${host}:${port}/ws`, [], {
        connectionTimeout: 3000,
        maxRetries: 10,
    })
}

function listenEvents(host, port, callback) {
    const socket = createWsConnection(host, port)

    socket.onmessage = event => callback(event)

    socket.addEventListener('open', () => {
        socket.send(`{"command":"join", "topics":["event"]}`)
        store.commit('ws/connect')
    });

    socket.addEventListener('close', () => {
        store.commit('ws/disconnect')
    });
}


export function init() {
    window.Sfdump = SfdumpFunc(window.document)
    const [host, port] = window.location.host.split(':')

    listenEvents(host, port, function (e) {
        const data = JSON.parse(e.data)

        if (data.topic === 'event') {
            const event = EventFactory.create(
                JSON.parse(data.payload.payload) || {type: 'null'}
            )

            if (event) {
                store.commit('pushEvent', event)
            }
        }
    })
}



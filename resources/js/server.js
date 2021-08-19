import {store} from "./store";
import SfdumpFunc from './Utils/dumper'
import ReconnectingWebSocket from 'reconnecting-websocket';
import EventFactory from "./EventFactory";

function createWsConnection(host, port) {
    return new ReconnectingWebSocket(`ws://${host}:${port}/`, [], {
        connectionTimeout: 3000,
        maxRetries: 10,
    })
}

function listenEvents(host, port, callback) {
    const socket = createWsConnection(host, port)

    socket.onmessage = callback

    socket.addEventListener('open', () => {
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
        const event = EventFactory.create(
            JSON.parse(e.data)
        )

        if (event) {
            store.commit('pushEvent', event)
        }
    })
}



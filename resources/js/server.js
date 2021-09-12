import {store} from "./store";
import SfdumpFunc from './Utils/dumper'
import Broadcast from './RoadRunner/Broadcast'
import EventFactory from "./EventFactory";

const [host, port] = window.location.host.split(':')
window.ws = new Broadcast({
    host: `ws://${host}:${port}/ws`,
    connectionTimeout: 3000,
    maxRetries: 10,
})

export function init() {
    window.Sfdump = SfdumpFunc(window.document)

    ws
        .onConnect(() => store.commit('ws/connect'))
        .onDisconnect(() => store.commit('ws/disconnect'))

    EventFactory.init()
}



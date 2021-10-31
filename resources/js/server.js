import {store} from "./store";
import SfdumpFunc from './Utils/dumper'
import Broadcast from './RoadRunner/Broadcast'
import EventFactory from "./EventFactory";
import TerminalFactory from "./TerminalFactory";

const host = window.location.host
const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:'

window.ws = new Broadcast({
    host: `${protocol}//${host}/ws`,
    connectionTimeout: 3000,
    maxRetries: 10,
})

export function init() {
    window.Sfdump = SfdumpFunc(window.document)

    ws
        .onConnect(() => store.commit('ws/connect'))
        .onDisconnect(() => store.commit('ws/disconnect'))

    EventFactory.init()
    TerminalFactory.init()
}



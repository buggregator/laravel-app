import {store} from "./store";

export default {
    subscribed: false,

    init() {
        if (this.subscribed) {
            return;
        }

        ws.listen('terminal', 'Websocket\\TerminalWrite', (payload) => {
            payload.payload.message.split("\n").forEach((line) => store.commit('terminal/push', line))
        })

        this.subscribed = true
    }
}

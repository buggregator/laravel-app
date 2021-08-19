import {Event} from "../Event"

export default class extends Event {
    labels = ['exception']
    color = 'pink'
    app = 'sentry'

    get serverName() {
        return this.event.server_name
    }

    get type() {
        return 'Sentry'
    }

    get payloads() {
        return this.event.exception.values
    }
}

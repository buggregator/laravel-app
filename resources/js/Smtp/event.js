import {Event} from "../Event"

export default class extends Event {
    labels = ['smtp']
    color = 'yellow'
    app = 'smtp'

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

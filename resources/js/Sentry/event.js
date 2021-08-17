import moment from "moment";

export default class {
    date = moment()
    labels = ['exception']
    color = 'pink'
    app = 'sentry'

    constructor(event) {
        this.event = event
        this.collapsed = false
    }

    setCollapsed(state) {
        this.collapsed = state
    }

    get serverName() {
        return this.event.server_name
    }

    get type() {
        return 'Sentry'
    }

    get uuid() {
        return this.event.event_id
    }

    get payloads() {
        return this.event.exception.values
    }

    isType(type) {
        return this.type === type
    }

    content(field) {
        return ''
    }

    merge(event) {

    }
}

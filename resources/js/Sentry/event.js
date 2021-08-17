import moment from "moment";

export default class {
    date = moment()
    labels = ['Sentry']
    color = 'pink'

    constructor(event) {
        this.event = event
        this.collapsed = false
    }

    setCollapsed(state) {
        this.collapsed = state
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

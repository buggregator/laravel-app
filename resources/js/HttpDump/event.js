import {Event} from "@/Event"

export default class extends Event{
    labels = []
    app = 'httpdump'

    constructor(event, id, timestamp) {
        super(event, id, timestamp)
        this.labels.push(this.event.request.method)
    }

    get type() {
        return 'httpdump'
    }
}

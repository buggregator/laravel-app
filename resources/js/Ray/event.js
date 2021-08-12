import moment from "moment";

export class RayEvent {
    date = moment()

    constructor(event) {
        this.event = event
    }

    get type() {
        return this.event.payloads[0].type
    }

    get uuid() {
        return this.event.uuid
    }

    get payloads() {
        return this.event.payloads || []
    }

    isType(type) {
        return this.type === type
    }

    content(field) {
        return this.event.payloads[0].content[field]
    }

    get color() {
        let color = 'gray'

        this.event.payloads.forEach(function (payload) {
            if (payload.content.color) {
                color = payload.content.color
            }
        })

        return color
    }

    get labels() {
        let labels = [];

        this.event.payloads.forEach(function (payload) {
            if (payload.content.label) {
                labels.push(payload.content.label)
            }

            labels.push(payload.type)
        })

        return _.uniq(labels)
    }
}

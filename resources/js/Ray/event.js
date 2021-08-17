import moment from "moment";

const labelsMap = {
    create_lock: 'Pause',
    trace: 'Trace',
    table: 'Table',
    caller: 'Caller',
    measure: 'Measure',
    event: 'Event',
    job: 'Job',
    json_string: 'Json',
    cache: 'Cache',
    view: 'View',
    eloquent_model: 'Eloquent model',
    executed_query: 'Query',
    application_log: 'Monolog',
    log: 'Log',
    custom: null
}

export default class {
    date = moment()
    labels = []
    color = 'gray'

    constructor(event) {
        this.event = event
        this.collapsed = false
        this.labels = this.collectLabels()
        this.color = this.detectColor()
    }

    setCollapsed(state) {
        this.collapsed = state
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

    detectColor() {
        let color = this.color

        this.event.payloads.forEach(function (payload) {
            if (payload.content.color) {
                color = payload.content.color
            }
        })

        return color
    }

    collectLabels() {
        let labels = [];

        this.event.payloads.forEach(function (payload) {
            if (payload.content.label) {
                const label = labelsMap.hasOwnProperty(payload.content.label)
                    ? labelsMap[payload.content.label]
                    : payload.content.label;

                if (label && !labels.includes(label)) {
                    labels.push(label)
                }
            }

            const typeLabel = labelsMap.hasOwnProperty(payload.type)
                ? labelsMap[payload.type]
                : payload.type;

            if (typeLabel && !labels.includes(typeLabel)) {
                labels.push(typeLabel)
            }
        })

        return labels
    }

    merge(event) {
        this.event = _.merge(event, this.event)
        this.labels = this.collectLabels()
        this.color = this.detectColor()
    }
}

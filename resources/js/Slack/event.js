import {Event} from "../Event"

const colorMap = {
    danger: 'red',
    warning: 'orange',
    good: 'green'
}

export default class extends Event{
    labels = ['monolog']
    app = 'monolog'

    constructor(event, id, timestamp) {
        super(event, id, timestamp)
        this.color = colorMap[this.payloads[0].color] || 'gray'
        this.labels.push(this.content('level'))
    }

    get type() {
        return 'monolog_slack'
    }

    get text() {
        return this.payloads[0].text
    }

    get payloads() {
        return this.event.attachments
    }

    get fields() {
        return this.payloads[0].fields.filter(f => {
            return f.title.toLowerCase() != 'level'
        })
    }

    content(key) {
        let value = null

        this.payloads[0].fields.forEach(f => {
            if (f.title.toLowerCase() === key) {
                value = f.value
            }
        })

        return value
    }
}

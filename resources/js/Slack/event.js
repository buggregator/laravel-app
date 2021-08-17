import moment from "moment";
import {v4 as uuidv4} from 'uuid';

const colorMap = {
    danger: 'red',
    warning: 'orange',
    good: 'green'
}

export default class {
    date = moment()
    labels = ['Monolog']
    id = uuidv4()
    app = 'monolog'

    constructor(event) {
        this.event = event
        this.collapsed = false
        this.color = colorMap[this.payloads[0].color] || 'gray'
        this.labels.push(this.content('level'))
    }

    setCollapsed(state) {
        this.collapsed = state
    }

    get type() {
        return 'monolog_slack'
    }

    get uuid() {
        return this.id
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

    isType(type) {
        return this.type === type
    }

    merge(event) {

    }
}

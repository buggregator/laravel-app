import {Event} from "../Event"
import moment from "moment";

const colorMap = {
    CRITICAL: 'red',
    ERROR: 'red',
    ALERT: 'red',
    EMERGENCY: 'red',
    WARNING: 'orange',
    INFO: 'blue',
    NOTICE: 'blue',
    DEBUG: 'gray',
    SUCCESS: 'green'
}

const segmentColor = {
    sqlite: '#FEB019',
    view: '#16a34a',
    artisan: '#2563eb',
}

const result = {
    SUCCESS: 'success',
    ERROR: 'error'
}

export default class extends Event {
    labels = ['inspector']
    color = 'gray'
    app = 'inspector'
    constructor(event, id, timestamp) {
        super(event, id, timestamp)
        this.color = colorMap[this.processResult] || 'gray'
        this.id = id;
    }

    get type() {
        return 'inspector'
    }

    get serverName() {
        return this.payloads[0].host.hostname
    }

    get payloads() {
        return this.event
    }

    get segments() {
        return this.payloads.filter((i) => i.model === 'segment' && this.process.hash === i.transaction.hash)
    }

    // TODO получить запрос (request), или он всегда первый или нужны какие то проверки на тип??
    get process() {
        return this.payloads[0]
    }

    get processDate() {
        return  moment.unix(this.process.timestamp)
    }

    get processResult() {
       return (this.process.result || result.SUCCESS).toUpperCase();
    }
    // TODO если нужно разные цвета у разных типов сегмента на графике
    setColorSegment(type) {
        return segmentColor[type] || '#FEB019';
    }
}

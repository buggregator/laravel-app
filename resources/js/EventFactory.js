import RayEvent, {EventHandler as RayEventHandler} from "./Ray/event";
import SentryEvent from "./Sentry/event";
import SlackEvent from "./Slack/event";
import MonologEvent from "./Monolog/event";
import SmtpEvent from "./Smtp/event";
import VarDumpEvent from "./VarDump/event";
import {store} from "./store";

const eventTypes = {
    ray: json => {
        const event = new RayEvent(json.data, json.uuid, json.timestamp);
        if (new RayEventHandler(event).handle()) {
            return event
        }
    },
    sentry: json => new SentryEvent(json.data, json.uuid, json.timestamp),
    slack: json => new SlackEvent(json.data, json.uuid, json.timestamp),
    monolog: json => new MonologEvent(json.data, json.uuid, json.timestamp),
    smtp: json => new SmtpEvent(json.data, json.uuid, json.timestamp),
    'var-dump': json => new VarDumpEvent(json.data, json.uuid, json.timestamp)
}

export default {
    subscribed: false,

    init() {
        if (this.subscribed) {
            return;
        }

        ws.listen('event', 'EventReceived', (payload) => {
            const event = this.create(payload.payload)
            if (event) {
                if (event instanceof SmtpEvent) {
                    store.commit('smtp/pushEvent', event)
                }

                store.commit('pushEvent', event)
            }
        })

        this.subscribed = true
    },

    create(json) {
        const type = json.type.toLowerCase()
        if (eventTypes.hasOwnProperty(type)) {
            return eventTypes[type](json)
        }
    }
}

import RayEvent, {EventHandler as RayEventHandler} from "./Ray/event";
import SentryEvent from "./Sentry/event";
import SlackEvent from "./Slack/event";
import MonologEvent from "./Monolog/event";
import SmtpEvent from "./Smtp/event";
import VarDumpEvent from "./VarDump/event";
import InspectorEvent from "./Inspector/event";
import {store} from "./store";

const eventTypes = {
    ray: json => {
        const event = new RayEvent(json.payload, json.uuid, json.timestamp);
        if (new RayEventHandler(event).handle()) {
            return event
        }
    },
    sentry: json => new SentryEvent(json.payload, json.uuid, json.timestamp),
    slack: json => new SlackEvent(json.payload, json.uuid, json.timestamp),
    monolog: json => new MonologEvent(json.payload, json.uuid, json.timestamp),
    smtp: json => new SmtpEvent(json.payload, json.uuid, json.timestamp),
    inspector: json => new InspectorEvent(json.payload, json.uuid, json.timestamp),
    'var-dump': json => new VarDumpEvent(json.payload, json.uuid, json.timestamp)
}

export default {
    subscribed: false,

    init() {
        if (this.subscribed) {
            return;
        }

        const namespace = '.Modules\\IncommingEvents\\Domain\\Events';

        ws.listen('event', `${namespace}\\EventWasReceived`, payload => {
            const event = this.create(payload.payload)
            if (!event) {
                return;
            }

            if (event instanceof SmtpEvent) {
                store.commit('smtp/pushEvent', event)
            } else if (event instanceof SentryEvent) {
                store.commit('sentry/pushEvent', event)
            } else if (event instanceof InspectorEvent) {
                store.commit('inspector/pushEvent', event)
            }

            store.commit('pushEvent', event)
        }).listen(`${namespace}\\EventWasDeleted`, e => {
            store.commit('deleteEvent', e.payload.uuid)
        }).listen(`${namespace}\\EventsWasClear`, e => {
            if (e.payload.type === 'smtp') {
                store.commit('smtp/clearEvents')
            }

            if (e.payload.type === 'sentry') {
                store.commit('sentry/clearEvents')
            }

            store.commit('clearEvents', e.payload.type)
        })

        this.subscribed = true
    },

    create(json) {
        const type = json.type.toLowerCase()

        if (eventTypes.hasOwnProperty(type)) {
            return eventTypes[type](json)
        }

        throw new Error(`Event type [${type}] is not found.`)
    }
}

import RayEvent, {EventHandler as RayEventHandler} from "./Ray/event";
import SentryEvent from "./Sentry/event";
import SlackEvent from "./Slack/event";
import SmtpEvent from "./Smtp/event";
import VarDumpEvent from "./VarDump/event";

const eventTypes = {
    ray: json => {
        const event = new RayEvent(json.data, json.uuid, json.timestamp);
        if (new RayEventHandler(event).handle()) {
            return event
        }
    },
    sentry: json => new SentryEvent(json.data, json.uuid, json.timestamp),
    slack: json => new SlackEvent(json.data, json.uuid, json.timestamp),
    smtp: json => new SmtpEvent(json.data, json.uuid, json.timestamp),
    'var-dump': json => new VarDumpEvent(json.data, json.uuid, json.timestamp)
}

export default {
    create(json) {
        const type = json.type.toLowerCase()
        if (eventTypes.hasOwnProperty(type)) {
            return eventTypes[type](json)
        }
    }
}

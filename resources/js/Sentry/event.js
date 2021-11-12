import {Event} from "../Event"

export default class extends Event {
    labels = ['exception']
    color = 'pink'
    app = 'sentry'

    get serverName() {
        return this.event.server_name
    }

    get type() {
        return 'Sentry'
    }

    get payload() {
        return this.event.exception.values[0] || {
            type: 'Unknown',
            value: 'Something went wrong',
            stacktrace: {
                frames: []
            }
        }
    }

    get request() {
        return this.event.request
    }

    get platform() {
        return this.event.platform
    }

    get logger() {
        return this.event.logger
    }

    get sdk() {
        return this.event.sdk
    }

    get os() {
        return this.event.contexts.os
    }

    get environment() {
        return this.event.environment
    }

    get runtime() {
        return this.event.contexts.runtime
    }

    get stacktrace() {
        return this.payload.stacktrace.frames.reverse()
    }

    get breadcrumbs() {
        return this.event.breadcrumbs.values || []
    }

    get location() {
        const lastElm = [this.stacktrace.length - 1];
        if (lastElm < 0) {
            return null
        }

        return this.stacktrace[lastElm]
    }
}

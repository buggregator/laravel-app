import {Event} from "@/Event"

export default class extends Event {
    labels = ['transaction']
    color = 'pink'
    app = 'sentryTransaction'

    constructor(event, id, timestamp, projectId, transactionId) {
        super(event, id, timestamp)

        this._contexts = event.contexts || {
            os: {},
            runtime: {},
            trace: {}
        }
        this.projectId = projectId
        this.transactionId = transactionId
    }

    get route() {
        return {
            index: route('events.transactions', [this.transactionId, this.projectId]),
            show: route('event.show', [this.app, this.id]),
            json: route('event.show.json', this.id),
            'delete': route('event.delete', this.id)
        }
    }

    get serverName() {
        return this.event.server_name
    }

    get type() {
        return 'SentryTransactionEvent'
    }

    get request() {
        return this.event.request
    }

    get transaction() {
        return this.event.transaction
    }

    get spans() {
        return this.event.spans
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
        return this._contexts.os
    }

    get environment() {
        return this.event.environment
    }

    get tags() {
        return this.event.tags
    }

    get runtime() {
        return this._contexts.runtime
    }

    get breadcrumbs() {
        return this.event.breadcrumbs.values || []
    }

    get contexts() {
        return this._contexts
    }
}

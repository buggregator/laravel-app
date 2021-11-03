import {EventFormatter} from './EventFormatter'

export default class Channel {
    ws;

    /**
     * The name of the channel.
     */
    name;

    /**
     * Channel options.
     */
    options;

    /**
     * The event formatter.
     */
    eventFormatter;

    /**
     * The event callbacks applied to the socket.
     */
    events = {};

    /**
     * User supplied callbacks for events on this channel.
     */
    listeners = {};

    /**
     * Create a new class instance.
     */
    constructor(ws, name, options) {
        this.name = name;
        this.ws = ws;
        this.options = options;
        this.eventFormatter = new EventFormatter(this.options.namespace);

        this.subscribe();
    }

    /**
     * Subscribe to a channel.
     */
    subscribe() {
        this.ws.send('{"command":"join", "topics":["' + this.name + '"]}');
    }

    /**
     * Listen for an event on the channel instance.
     */
    listen(event, callback) {
        this.on(this.eventFormatter.format(event), callback);

        return this;
    }

    /**
     * Register a callback to be called anytime an error occurs.
     */
    error(callback) {
        return this;
    }

    /**
     * Bind the channel's socket to an event and store the callback.
     */
    on(event, callback) {
        this.listeners[event] = this.listeners[event] || [];

        if (!this.events[event]) {
            this.events[event] = (e) => {
                const data = JSON.parse(e.data)
                if (data.topic === this.name) {
                    const payload = JSON.parse(data.payload) || {event: 'null'};

                    if (payload.event === event && this.listeners[event]) {
                        this.listeners[event].forEach((cb) => cb(payload));
                    }
                }
            };

            this.ws.addEventListener('message', this.events[event])
        }

        this.listeners[event].push(callback);

        return this;
    }

    unsubscribe() {
        this.events.forEach(
            callback => this.ws.removeEventListener('message', callback)
        )
    }
}

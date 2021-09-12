import ReconnectingWebSocket from 'reconnecting-websocket';
import Channel from './Channel'

class Connector {
    _defaultOptions = {
        auth: {
            headers: {},
        },
        authEndpoint: '/ws',
        csrfToken: null,
        host: null,
        namespace: 'App.Events',
    };

    options;

    /**
     * Create a new class instance.
     */
    constructor(options) {
        this.setOptions(options);
        this.connect();
    }

    setOptions(options) {
        this.options = Object.assign(this._defaultOptions, options);

        if (this.csrfToken()) {
            this.options.auth.headers['X-CSRF-TOKEN'] = this.csrfToken();
        }

        return options;
    }

    csrfToken() {
        let selector;

        if (this.options.csrfToken) {
            return this.options.csrfToken;
        } else if (
            typeof document !== 'undefined' &&
            typeof document.querySelector === 'function' &&
            (selector = document.querySelector('meta[name="csrf-token"]'))
        ) {
            return selector.getAttribute('content');
        }

        return null;
    }

    /**
     * Create a fresh connection.
     */
    connect() {
    }

    /**
     * Get a channel instance by name.
     */
    channel(channel) {
    }

    /**
     * Leave the given channel, as well as its private and presence variants.
     */
    leave(channel) {
    }

    /**
     * Disconnect from the Echo server.
     */
    disconnect() {
    }
}

export default class WebsocketConnector extends Connector {
    channels = {}

    connect() {
        this.ws = new ReconnectingWebSocket(
            this.options.host,
            this.options.protocols || [],
            this.options
        )

        return this.ws;
    }

    listen(name, event, callback) {
        return this.channel(name).listen(event, callback);
    }

    channel(name) {
        if (!this.channels[name]) {
            this.channels[name] = new Channel(this.ws, name, this.options);
        }

        return this.channels[name];
    }

    disconnect() {
        this.ws.close();
    }

    leave(name) {
        let channels = [name];

        channels.forEach((name) => {
            this.leaveChannel(name);
        });
    }

    /**
     * Leave the given channel.
     */
    leaveChannel(name) {
        if (this.channels[name]) {
            this.channels[name].unsubscribe();

            delete this.channels[name];
        }
    }
}

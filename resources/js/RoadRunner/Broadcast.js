import WebsocketConnector from './WebsocketConnector'

export default class Broadcast {
    connector;
    options;

    /**
     * Create a new class instance.
     */
    constructor(options) {
        this.options = options;
        this.connect();
    }

    /**
     * Get a channel instance by name.
     */
    channel(channel) {
        return this.connector.channel(channel);
    }

    /**
     * Create a new connection.
     */
    connect() {
        this.connector = new WebsocketConnector(this.options);
    }

    onConnect(callback) {
        this.connector.ws.addEventListener('open', callback)

        return this
    }

    /**
     * Disconnect from the Echo server.
     */
    disconnect() {
        this.connector.disconnect();
    }

    onDisconnect(callback) {
        this.connector.ws.addEventListener('close', callback)

        return this
    }

    /**
     * Get a presence channel instance by name.
     */
    join(channel) {
        return this.connector.channel(channel);
    }

    /**
     * Leave the given channel, as well as its private and presence variants.
     */
    leave(channel) {
        this.connector.leave(channel);
    }

    /**
     * Leave the given channel.
     */
    leaveChannel(channel) {
        this.connector.leaveChannel(channel);
    }

    /**
     * Listen for an event on a channel instance.
     */
    listen(channel, event, callback) {
        return this.connector.listen(channel, event, callback);
    }
}

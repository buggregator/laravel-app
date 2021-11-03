/**
 * Event name formatter
 */
export class EventFormatter {
    /**
     * Event namespace.
     */
    namespace;

    /**
     * Create a new class instance.
     */
    constructor(namespace) {
        this.setNamespace(namespace);
    }

    /**
     * Format the given event name.
     */
    format(event) {
        if (event.charAt(0) === '.' || event.charAt(0) === '\\') {
            return event.substr(1);
        } else if (this.namespace) {
            event = this.namespace + '.' + event;
        }

        return event.replace(/\./g, '\\');
    }

    /**
     * Set the event namespace.
     */
    setNamespace(value) {
        this.namespace = value;
    }
}

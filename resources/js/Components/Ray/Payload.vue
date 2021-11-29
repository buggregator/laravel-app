<template>
    <component
        class="flex-grow"
        :is="component"
        :payload="payload"
        :disabled="disabled"
        v-on:disable="$emit('disable')"
        v-on:delete="deleteEvent"
    ></component>
</template>

<script>
const componentsMap = {
    custom: 'Custom',
    trace: 'Trace',
    caller: 'Caller',
    executed_query: 'Query',
    log: 'Values',
    measure: 'Measure',
    json_string: 'Json',
    carbon: 'Carbon',
    table: 'Table',
    job_event: 'Job',
    eloquent_model: 'EloquentModel',
    view: 'View',
    response: 'Response',
    exception: 'Exception',
    create_lock: 'Pause',
    event: 'Event',
    mailable: 'Mailable',
    application_log: 'ApplicationLog',
    default: 'Default'
}

let components = {};

_.each(componentsMap, (name, type) => {
    components[name] = require(`./Payloads/${name}`).default
})

export default {
    components: {...components},
    props: {
        payload: Object,
        disabled: {
            type: Boolean,
            default: true
        }
    },
    methods: {
        deleteEvent() {
            this.$emit('delete')
        }
    },
    computed: {
        component() {
            if (componentsMap.hasOwnProperty(this.payload.type)) {
                return componentsMap[this.payload.type]
            }

            return componentsMap.default;
        },
    }
}
</script>

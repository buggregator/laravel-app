<template>
    <div class="flex flex-col justify-items-stretch">
        <component class="flex-grow" :is="component" :payload="payload"></component>
        <Origin class="mt-4" :origin="payload.origin" />
    </div>
</template>

<script>
import LogValue from "./Value";
import LogCustom from "./Custom";
import LogTrace from "./Trace";
import LogCaller from "./Caller";
import LogQuery from "./Query";
import LogEvent from "./Event";
import Origin from "./Origin";

export default {
    components: {LogValue, LogCustom, LogTrace, LogCaller, LogQuery, LogEvent, Origin},
    props: {
        payload: Object
    },
    computed: {
        component() {
            switch (this.payload.type) {
                case 'custom':
                    return 'LogCustom'
                case 'trace':
                    return 'LogTrace'
                case 'caller':
                    return 'LogCaller'
                case 'executed_query':
                    return 'LogQuery'
                case 'event':
                    return 'LogEvent'
            }
            return 'LogValue';
        },
    }
}
</script>

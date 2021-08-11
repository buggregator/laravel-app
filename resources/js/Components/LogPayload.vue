<template>
    <div class="flex flex-col justify-items-stretch">
        <component class="flex-grow" :is="component" :payload="payload"></component>
        <Origin class="mt-4" :origin="payload.origin" />
    </div>
</template>

<script>
import LogValue from "./LogValue";
import LogCustom from "./LogCustom";
import LogTrace from "./LogTrace";
import LogCaller from "./LogCaller";
import LogQuery from "./LogQuery";
import LogEvent from "./LogEvent";
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

<template>
    <div class="flex flex-col justify-items-stretch w-full lg:w-2/3">
        <component class="flex-grow" :is="component" :payload="payload"></component>
        <Origin class="mt-1" :origin="payload.origin" />
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
import LogDefault from "./Default";
import Measure from "./Measure";
import Json from "./Json";
import Carbon from "./Carbon";
import Table from "./Table";
import Job from "./Job";
import EloquentModel from "./EloquentModel";
import View from "./View";
import Response from "./Response";
import Exception from "./Exception";

export default {
    components: {
        LogValue, LogCustom, LogTrace, LogCaller, LogQuery, LogEvent, Exception,
        LogDefault, Measure, Json, Carbon, Table, Job, EloquentModel, View, Origin, Response
    },
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
                case 'log':
                    return 'LogValue';
                case 'measure':
                    return 'Measure';
                case 'json_string':
                    return 'Json';
                case 'carbon':
                    return 'Carbon';
                case 'table':
                    return 'Table';
                case 'job_event':
                    return 'Job';
                case 'eloquent_model':
                    return 'EloquentModel';
                case 'view':
                    return 'View';
                case 'response':
                    return 'Response';
                case 'exception':
                    return 'Exception';
            }

            return 'LogDefault';
        },
    }
}
</script>

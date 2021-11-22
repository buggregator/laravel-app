<template>
    <div>
        <h3 v-if="payload.content.is_new_timer" class="font-bold">
            Start measuring performance...
        </h3>

        <Table v-else>
            <TableRow title="Total time">
                {{ totalTime }} s
            </TableRow>
            <TableRow title="Time since last call">
                {{ timeSinceLastCall }} s
            </TableRow>
            <TableRow title="Maximum memory usage">
                {{ maxMemoryUsage }}
            </TableRow>
        </Table>
    </div>
</template>

<script>
import Table from "@/Components/UI/Table";
import TableRow from "@/Components/UI/TableRow";

function prettySize(bytes, separator = '', postFix = '') {
    if (bytes) {
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.min(parseInt(Math.floor(Math.log(bytes) / Math.log(1024)), 10), sizes.length - 1);
        return `${(bytes / (1024 ** i)).toFixed(2)}${separator} ${sizes[i]}${postFix}`;
    }
    return 'n/a';
}

function convertMiliseconds(miliseconds) {
    return (miliseconds / 1000).toFixed(4);
}

export default {
    components: {TableRow, Table},
    props: {
        payload: Object,
    },
    computed: {
        totalTime() {
            return convertMiliseconds(this.payload.content.total_time)
        },
        timeSinceLastCall() {
            return convertMiliseconds(this.payload.content.time_since_last_call)
        },
        maxMemoryUsage() {
            return prettySize(this.payload.content.max_memory_usage_during_total_time)
        }
    }
}
</script>

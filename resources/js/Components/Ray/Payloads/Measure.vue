<template>
    <div ref="trace">
        <h3 v-if="payload.content.is_new_timer" class="text-gray-800 font-bold">
            Start measuring performance...
        </h3>

        <Table v-else>
            <TableRow title="Total time">
                {{ payload.content.total_time.toFixed(2) }}ms
            </TableRow>
            <TableRow title="Maximum memory usage">
                {{ payload.content.max_memory_usage_during_total_time.toFixed(2) }} KB
            </TableRow>
            <TableRow title="Time since last call">
                {{ payload.content.time_since_last_call.toFixed(2) }}ms
            </TableRow>
        </Table>
    </div>
</template>

<script>
import Table from "@/Components/UI/Table";
import TableRow from "@/Components/UI/TableRow";
export default {
    components: {TableRow, Table},
    props: {
        payload: Object
    }
}
</script>

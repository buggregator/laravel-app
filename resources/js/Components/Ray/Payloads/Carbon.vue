<template>
    <div ref="trace">
        <Table>
            <TableRow title="Formatted">
                {{ payload.content.formatted }}
            </TableRow>
            <TableRow title="Timezone">
                {{ payload.content.timezone }}
            </TableRow>
            <TableRow title="Timestamp">
                {{ payload.content.timestamp }}
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

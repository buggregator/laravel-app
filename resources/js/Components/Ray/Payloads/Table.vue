<template>
    <div ref="trace">
        <Table>
            <TableRow :title="title" v-for="(value, title) in payload.content.values">
                <span v-html="value"></span>
            </TableRow>
        </Table>
    </div>
</template>

<script>
import Table from "@/Components/UI//Table";
import TableRow from "@/Components/UI//TableRow";

export default {
    components: {TableRow, Table},
    props: {
        payload: Object
    }
}
</script>


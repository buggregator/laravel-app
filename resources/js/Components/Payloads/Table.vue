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
import Table from "../UI/Table";
import TableRow from "../UI/TableRow";

export default {
    components: {TableRow, Table},
    props: {
        payload: Object
    }
}
</script>


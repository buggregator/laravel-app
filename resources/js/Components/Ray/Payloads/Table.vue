<template>
    <div>
        <Table>
            <TableRow :title="title" v-for="(value, title) in payload.content.values">
                <Dump :value="value" />
            </TableRow>
        </Table>
    </div>
</template>

<script>
import Dump from "@/Components/UI/Dump";
import Table from "@/Components/UI/Table";
import TableRow from "@/Components/UI/TableRow";

export default {
    components: {TableRow, Table, Dump},
    props: {
        payload: Object
    },
    methods: {
        isDump(value) {
            if (typeof value == 'string') {
                return value.includes('sf-dump')
            }

            return false
        },
    }
}
</script>


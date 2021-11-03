<template>
    <div>
        <CodeSnippet language="sql" class="border-b-0">
            {{ formattedSql }}
        </CodeSnippet>
        <Table>
            <TableRow title="Connection name">
                {{ this.payload.content.connection_name }}
            </TableRow>
            <TableRow title="Time">
                {{ this.payload.content.time }}ms
            </TableRow>
        </Table>
    </div>
</template>

<script>
import CodeSnippet from "@/Components/UI/CodeSnippet"
import Table from "@/Components/UI/Table";
import TableRow from "@/Components/UI/TableRow";

export default {
    components: {TableRow, Table, CodeSnippet},
    props: {
        payload: Object
    },
    computed: {
        formattedSql() {
            return this.payload.content.bindings.reduce((sql, currentValue) => {
                return sql.replace(/\?/, `'${currentValue}'`)
            }, this.payload.content.sql)
        }

    }
}
</script>

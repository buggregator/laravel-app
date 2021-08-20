<template>
    <div>
        <ssh-pre language="sql" class="border-b-0">
            {{ formattedSql }}
        </ssh-pre>
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
import SshPre from 'simple-syntax-highlighter'
import Table from "@/Components/UI//Table";
import TableRow from "@/Components/UI//TableRow";

export default {
    components: {TableRow, Table, SshPre},
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

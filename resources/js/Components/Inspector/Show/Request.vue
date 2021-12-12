<template>
    <section class="inspector-request">
        <h3 class="inspector-request__title">Request</h3>
        <Table class="inspector-request__table">
            <TableRow :title="name" v-for="(value, name) in event.process.http.request">
                <template v-if="typeof value==='string'">
                    {{ value }}
                </template>
                <template v-else-if="!Array.isArray(value)">
                    <TableRow :title="n" v-for="(v, n) in value">
                        {{ v }}
                    </TableRow>
                </template>
            </TableRow>
        </Table>
    </section>
</template>

<script>
import Table from "@/Components/UI/Table";
import TableRow from "@/Components/UI/TableRow";

export default {
    components: {Table, TableRow},
    props: ['event']
}
</script>

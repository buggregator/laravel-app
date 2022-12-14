<template>
    <section class="py-5 px-4 md:px-6 lg:px-8 border-b">
        <h1 class="mb-1 text-lg bold">Query Parameters</h1>
        <Table>
            <TableRow :title="title" v-for="(value, title) in event.request.query">
                {{ value }}
            </TableRow>
        </Table>
    </section>
</template>

<script>
import Table from "@/Components/UI/Table";
import TableRow from "@/Components/UI/TableRow";

export default {
    components: {
        Table, TableRow
    },
    props: {
        event: Object
    }
}
</script>

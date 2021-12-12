<template>
    <section class="inspector-url">
        <h3 class="inspector-url__title">Url</h3>
        <Table class="inspector-url__table">
            <TableRow :title="name" v-for="(value, name) in event.process.http.url">
                {{ value }}
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

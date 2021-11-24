<template>
    <section class="py-5 px-4 md:px-6 lg:px-8 border-b">
        <h3 class="text-gray-400 font-bold uppercase text-sm mb-5">Url</h3>
        <Table class="mt-3">
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

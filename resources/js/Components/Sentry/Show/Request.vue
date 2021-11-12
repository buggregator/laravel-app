<template>
    <section class="py-5 px-4 md:px-6 lg:px-8 border-b" v-if="event.request">
        <h3 class="text-gray-400 font-bold uppercase text-sm mb-5">request</h3>

        <h3 class="text-gray-900 mb-1 text-lg font-medium">
            <strong>{{ event.request.method }}</strong> {{ event.request.url }}
        </h3>

        <Table>
            <TableRow :title="title" v-for="(value, title) in event.request.headers">
                {{ value[0] || value}}
            </TableRow>
        </Table>
    </section>
</template>

<script>
import Table from "@/Components/UI/Table";
import TableRow from "@/Components/UI/TableRow";
import {Link} from "@inertiajs/inertia-vue3";

export default {
    components: {
        Table, TableRow,
    },
    props: {
        event: Object
    }
}
</script>

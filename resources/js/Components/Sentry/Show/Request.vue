<template>
    <section class="sentry-request" v-if="event.request">
        <h3 class="sentry-request__title">request</h3>

        <h3 class="sentry-request__text">
            <strong>{{ event.request.method }}</strong> {{ event.request.url }}
        </h3>

        <Table class="sentry-request__table">
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

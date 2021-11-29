<template>
    <div class="event-ray__mailable">
        <h3 v-if="payload.content.subject" class="font-bold">
            {{ payload.content.subject }}
        </h3>
        <div v-if="payload.content.mailable_class" class="event-ray__mailable-class">
            {{ payload.content.mailable_class }}
        </div>
        <Table class="event-ray__mailable-table">
            <TableRow title="From">
                <template v-for="user in payload.content.from">
                    {{ user.email }} <span class="event-ray__mailable-row-text" v-if="user.name">[{{ user.name }}]</span>
                </template>
            </TableRow>
            <TableRow title="To">
                <template v-for="user in payload.content.to">
                    {{ user.email }} <span class="event-ray__mailable-row-text" v-if="user.name">[{{ user.name }}]</span>
                </template>
            </TableRow>
            <TableRow title="Cc">
                <template v-for="user in payload.content.cc">
                    {{ user.email }} <span class="event-ray__mailable-row-text" v-if="user.name">[{{ user.name }}]</span>
                </template>
            </TableRow>
            <TableRow title="Bcc">
                <template v-for="user in payload.content.bcc">
                    {{ user.email }} <span class="event-ray__mailable-row-text" v-if="user.name">[{{ user.name }}]</span>
                </template>
            </TableRow>
        </Table>

        <Collapsed class="mt-3 event-ray__mailable-collapsed" title="HTML">
            <Dump :value="payload.content.html" class="collapsed__mailable"/>
        </Collapsed>
    </div>
</template>

<script>
import Table from "@/Components/UI/Table";
import TableRow from "@/Components/UI/TableRow";
import Dump from "@/Components/UI/Dump";
import Collapsed from "@/Components/UI/Collapsed";

export default {
    components: {TableRow, Table, Dump, Collapsed},
    props: {
        payload: Object
    }
}
</script>

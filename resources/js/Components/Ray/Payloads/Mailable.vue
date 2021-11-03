<template>
    <div>
        <h3 v-if="payload.content.subject" class="text-gray-800 font-bold">
            {{ payload.content.subject }}
        </h3>
        <div v-if="payload.content.mailable_class" class="text-gray-600 text-sm break-all">
            {{ payload.content.mailable_class }}
        </div>
        <Table>
            <TableRow title="From">
                <template v-for="user in payload.content.from">
                    {{ user.email }} <span class="text-gray-600 font-bold" v-if="user.name">[{{ user.name }}]</span>
                </template>
            </TableRow>
            <TableRow title="To">
                <template v-for="user in payload.content.to">
                    {{ user.email }} <span class="text-gray-600 font-bold" v-if="user.name">[{{ user.name }}]</span>
                </template>
            </TableRow>
            <TableRow title="Cc">
                <template v-for="user in payload.content.cc">
                    {{ user.email }} <span class="text-gray-600 font-bold" v-if="user.name">[{{ user.name }}]</span>
                </template>
            </TableRow>
            <TableRow title="Bcc">
                <template v-for="user in payload.content.bcc">
                    {{ user.email }} <span class="text-gray-600 font-bold" v-if="user.name">[{{ user.name }}]</span>
                </template>
            </TableRow>
        </Table>

        <Collapsed class="mt-3" title="HTML">
            <Dump :value="payload.content.html"/>
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

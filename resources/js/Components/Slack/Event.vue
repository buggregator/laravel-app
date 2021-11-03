<template>
    <Event :event="event">
        <div class="text-xs break-all">
            <CodeSnippet class="border-b-0">
                {{ event.text }}
            </CodeSnippet>
            <Table v-if="hasFields">
                <TableRow :title="field.title" v-for="field in fields">
                    <div v-html="value(field.value)"></div>
                </TableRow>
            </Table>
        </div>
    </Event>
</template>

<script>
import CodeSnippet from "@/Components/UI/CodeSnippet"
import Table from "@/Components/UI/Table";
import TableRow from "@/Components/UI/TableRow";
import Event from "../Event";

export default {
    components: {Event, TableRow, Table, CodeSnippet},
    props: {
        event: Object,
    },
    computed: {
        fields() {
            return this.event.fields
        },
        hasFields() {
            return this.fields.length > 0
        }
    },
    methods: {
        value(string) {
            if (typeof string == 'string') {
                return string.replace(/```([^]+?.*?[^]+?[^]+?)```/g, '<pre class="overflow-x-scroll bg-white p-2">$1</pre>')
            }

            return string
        }
    }
}
</script>

<template>
    <Event :event="event">
        <div class="text-xs break-all">
            <CodeSnippet class="border-b-0 mt-2">
                {{ event.text }}
            </CodeSnippet>

            <CodeSnippet v-if="hasPayloads" language="json" class="border-b-0">
                {{ event.payloads }}
            </CodeSnippet>

            <Table v-if="hasFields">
                <TableRow :title="key" v-for="(value, key) in fields">
                    <div v-html="value"></div>
                </TableRow>
            </Table>
        </div>
    </Event>
</template>

<script>
import CodeSnippet from "@/Components/UI/CodeSnippet"
import Table from "@/Components/UI/Table"
import TableRow from "@/Components/UI/TableRow"
import Event from "../Event"

export default {
    components: {Event, TableRow, Table, CodeSnippet},
    props: {
        event: Object,
    },
    computed: {
        fields() {
            return this.event.fields
        },
        hasPayloads() {
           return !_.isEmpty(this.event.payloads)
        },
        hasFields() {
            return this.fields.length > 0
        }
    },
}
</script>

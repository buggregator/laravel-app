<template>
    <Event :event="event" class="event--slack">
        <div class="event-slack__wrap">
            <CodeSnippet class="event-slack__snippet">
                {{ event.text }}
            </CodeSnippet>
            <Table v-if="hasFields" class="event-slack__table">
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
                return string.replace(/```([^]+?.*?[^]+?[^]+?)```/g, '<pre class="event-slack__pre-value">$1</pre>')
            }

            return string
        }
    }
}
</script>

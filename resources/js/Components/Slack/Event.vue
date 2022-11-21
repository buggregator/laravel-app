<template>
    <Event :event="event" class="event--slack">
        <div class="event-slack__wrap">
            <CodeSnippet class="event-slack__snippet">
                {{ event.text }}
            </CodeSnippet>
            <CodeSnippet v-if="hasFields" :title="field.title" v-for="field in fields">
                {{ value(field.value) }}
            </CodeSnippet>
        </div>
    </Event>
</template>

<script>
import CodeSnippet from "@/Components/UI/CodeSnippet"
import Table from "@/Components/UI/Table";
import TableRow from "@/Components/UI/TableRow";
import Event from "../Event";

export default {
    components: { Event, TableRow, Table, CodeSnippet},
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
                return string.replace(/```([^]+?.*?[^]+?[^]+?)```/g, '$1')
            }

            return string
        }
    }
}
</script>

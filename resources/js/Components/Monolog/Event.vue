<template>
    <Event :event="event" class="event--monolog">
        <div class="event-monolog__wrap">
            <CodeSnippet class="event-monolog__snippet text-white mt-0">
                {{ event.text }}
            </CodeSnippet>

            <CodeSnippet v-if="hasPayloads" language="json" class="event-monolog__payloads">
                {{ event.payloads }}
            </CodeSnippet>

            <CodeSnippet v-if="hasFields" :title="field.title" v-for="field in fields">
                {{ field.value }}
            </CodeSnippet>
            <div class="text-right">
                <a :href="event.route.json" target="_blank" class="text-xs text-blue-800 dark:text-blue-100 ml-auto">[JSON]</a>
            </div>
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

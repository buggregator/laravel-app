<template>
    <div class="event-ray__default">
        <CodeSnippet language="json" class="event-ray__default-snippet">
            {{ json }}
        </CodeSnippet>
    </div>
</template>

<script>
import CodeSnippet from "@/Components/UI/CodeSnippet"

export default {
    components: {CodeSnippet},
    name: "Default",
    props: {
        payload: Object
    },
    computed: {
        json() {
            return JSON.stringify(this.payload, null, 2)
        }
    }
}
</script>

<style scoped>

</style>

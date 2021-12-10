<template>
    <CodeSnippet class="event-ray__application">
        {{ payload.content.value }}
    </CodeSnippet>
</template>

<script>
import CodeSnippet from "@/Components/UI/CodeSnippet"
export default {
    components: {CodeSnippet},
    props: {
        payload: Object
    }
}
</script>

<template>
    <div>
        <CodeSnippet language="json">
            {{ json }}
        </CodeSnippet>
    </div>
</template>

<script>
import CodeSnippet from "@/Components/UI/CodeSnippet"

export default {
    components: {CodeSnippet},
    props: {
        payload: Object
    },
    computed: {
        json() {
            let json = this.payload.content.value;

            if (typeof json == 'string') {
                json = JSON.parse(json)
            }

            return JSON.stringify(json, null, 2)
        }
    }
}
</script>

<style scoped>

</style>

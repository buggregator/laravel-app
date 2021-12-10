<template>
    <div class="event-ray__response">
        <h3>
            Status code: <code class="font-semibold">{{ payload.content.status_code }}</code>
        </h3>

        <div v-if="payload.content.headers" class="mt-3">
            <h3 class="font-semibold">Headers</h3>
            <Dump :value="payload.content.headers"/>
        </div>

        <Collapsed class="mt-3 event-ray__response-collapsed" v-if="payload.content.content" title="HTML">
            <CodeSnippet language="html" class="event-ray__response-snippet">
                {{ payload.content.content }}
            </CodeSnippet>
        </Collapsed>

        <div v-if="payload.content.json" class="mt-3">
            <h3 class="font-semibold">JSON</h3>
            <Dump :value="payload.content.json"/>
        </div>
    </div>
</template>

<script>
import CodeSnippet from "@/Components/UI/CodeSnippet"
import Dump from "@/Components/UI/Dump";
import Collapsed from "@/Components/UI/Collapsed";

export default {
    components: {Collapsed, Dump, CodeSnippet},
    props: {
        payload: Object
    }
}
</script>

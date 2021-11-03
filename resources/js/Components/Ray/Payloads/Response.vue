<template>
    <div>
        <h3 class="text-gray-800">
            Status code: <code class="font-semibold">{{ payload.content.status_code }}</code>
        </h3>

        <div v-if="payload.content.headers" class="mt-3">
            <h3 class="text-gray-800 font-semibold">
                Headers
            </h3>

            <Dump :value="payload.content.headers"/>
        </div>

        <Collapsed class="mt-3" v-if="payload.content.content" title="HTML">
            <CodeSnippet language="html">
                {{ payload.content.content }}
            </CodeSnippet>
        </Collapsed>

        <div v-if="payload.content.json" class="mt-3">
            <h3 class="text-gray-800 font-semibold">
                JSON
            </h3>
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

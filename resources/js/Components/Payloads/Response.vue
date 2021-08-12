<template>
    <div ref="trace">
        <h3 class="text-gray-800">
            Class name: <code class="font-semibold">{{ payload.content.class_name }}</code>
        </h3>

        <div v-if="payload.content.headers" class="mt-3">
            <h3 class="text-gray-800 font-semibold">
                Headers
            </h3>
            <Dump :value="payload.content.headers"/>
        </div>

        <div v-if="payload.content.content" class="mt-3">
            <h3 class="text-gray-800 font-semibold">
                HTML
            </h3>

            <ssh-pre language="html" class="text-sm">
                {{ payload.content.content }}
            </ssh-pre>
        </div>

        <div v-if="payload.content.json" class="mt-3">
            <h3 class="text-gray-800 font-semibold">
                JSON
            </h3>
            <Dump :value="payload.content.json"/>
        </div>
    </div>
</template>

<script>
import SshPre from 'simple-syntax-highlighter'
import Dump from "./Dump";

export default {
    components: {Dump, SshPre},
    props: {
        payload: Object
    }
}
</script>

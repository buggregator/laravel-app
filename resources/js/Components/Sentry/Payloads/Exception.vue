<template>
    <div ref="trace">
        <div class="p-3 py-2 bg-gray-100 border rounded-t">
            <h3 class="text-gray-800 mb-1">
                <code class="font-semibold">{{ payload.type }}</code>
            </h3>
            <div class="text-gray-600 text-sm break-all">
                {{ payload.value }}
            </div>
        </div>
        <div class="border-b border-l border-r flex-col justify-center">
            <File :file="file" v-for="(file, i) in stacktrace" :collapsed="i !== 0"/>
        </div>
    </div>
</template>

<script>
import File from "../UI/File";

export default {
    components: {File},
    props: {
        payload: Object
    },
    computed: {
        stacktrace() {
            return this.payload.stacktrace.frames.reverse()
        }
    }
}
</script>

<template>
    <div>
        <div class="p-3 bg-gray-100 border rounded-t">
            <h3 class="text-gray-800 mb-1">
                <code class="font-semibold">{{ payload.content.class }}</code>
            </h3>
            <div class="text-gray-600 text-sm break-all">
                {{ payload.content.message }}
            </div>
        </div>
        <div class="border-b border-l border-r divide-y flex-col justify-center">
            <File :file="file" v-for="(file, i) in payload.content.frames" />
        </div>
    </div>
</template>

<script>
import File from "../UI/File";

export default {
    components: {File},
    props: {
        payload: Object
    }
}
</script>

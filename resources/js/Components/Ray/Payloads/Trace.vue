<template>
    <div ref="trace">
        <div class="bg-gray-50 border border-gray-200 py-2 flex-col justify-center space-y-2">
            <div class="border-b border-l border-r border-gray-200 flex-col justify-center">
                <File :file="file" v-for="(file, i) in payload.content.frames" />
            </div>
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

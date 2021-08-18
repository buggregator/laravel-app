<template>
    <div ref="trace">
        <div class="flex-col justify-center border divide-y">
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

<template>
    <div class="event-ray__trace">
        <div class="event-ray__trace-files">
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

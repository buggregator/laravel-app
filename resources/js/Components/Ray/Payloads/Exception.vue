<template>
    <div class="event-ray__exception">
        <div class="event-ray__exception-wrap">
            <h3 class="mb-1">
                <code class="font-semibold">{{ payload.content.class }}</code>
            </h3>
            <div class="event-ray__exception-text">
                {{ payload.content.message }}
            </div>
        </div>
        <div class="event-ray__exception-files">
            <File
                :file="file"
                v-for="(file, i) in payload.content.frames"
                :collapsed="i !== 0"
                class="event-ray__exception-file"
            />
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

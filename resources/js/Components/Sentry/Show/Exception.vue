<template>
    <div class="py-5">
        <h3 class="mb-1 text-xl font-bold">
            {{ exception.type }}
        </h3>
        <div class="text-muted break-all mb-5">
            {{ exception.value }}
        </div>
        <div class="border border-purple-200 text-muted">
            <File :file="file" v-for="(file, i) in exception.stacktrace.frames" :collapsed="i !== 0"/>
        </div>
    </div>
</template>

<script>
import File from "../UI/File";

export default {
    components: {File},
    props: {
        exception: Object
    },
}
</script>

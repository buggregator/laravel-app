<template>
    <span
        class="text-gray-400 underline text-2xs"
        :title="`${origin.file}:${origin.line_number}`"
    >
        {{ filename }}:{{ origin.line_number }}
    </span>
</template>

<script>
export default {
    props: {
        origin: Object
    },
    computed: {
        filename() {
            return this.origin.file.replace(/^.*[\\\/]/, '')
        }
    }
}
</script>

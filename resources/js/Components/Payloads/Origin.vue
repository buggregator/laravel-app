<template>
    <a
        class="text-gray-400 underline text-xs"
        :title="`${origin.file}:${origin.line_number}`"
        :href='`phpstorm://open?file=${encodeURIComponent(origin.file)}&line=${origin.line_number}`'>
        {{ filename }}:{{ origin.line_number }}
    </a>
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

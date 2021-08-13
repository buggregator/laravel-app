<template>
    <div ref="trace">
        <div>
            <h3 class="text-gray-800">Called from </h3>
            <a
                class="text-blue-400 underline"
                :href='`phpstorm://open?file=${encodeURIComponent(file.file_name)}&line=${file.line_number}`'>
                <code class="font-semibold break-all">{{ file.class || 'null' }}:{{ file.method }}</code>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        payload: Object
    },
    computed: {
        file() {
            return this.payload.content.frame
        }
    }
}
</script>

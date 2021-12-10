<template>
    <div class="event-ray__caller">
        <h3>Called from </h3>
        <a
            class="event-ray__caller-name"
            :href='`phpstorm://open?file=${encodeURIComponent(file.file_name)}&line=${file.line_number}`'>
            <code class="event-ray__caller-code">{{ file.class || 'null' }}:{{ file.method }}</code>
        </a>
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

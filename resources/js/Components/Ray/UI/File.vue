<template>
    <div class="event-ray-file" @click="collapsed = !collapsed">
        <div class="event-ray-file__header">
            <div :title="file.file_name" class="event-ray-file__title">
                <div>
                    {{ file.class || 'null' }}:{{ file.method }}
                    <span class="text-muted">at line</span>
                    {{ file.line_number }}
                </div>
                <span class="text-muted">{{ file.file_name }}</span>
            </div>
            <div class="event-ray-file__icon" v-if="hasSnippet">
                <svg viewBox="0 0 16 16"
                     fill="currentColor"
                     height="100%" width="100%"
                     :class="{'transform rotate-180': collapsed}"
                >
                    <path d="M14,11.75a.74.74,0,0,1-.53-.22L8,6.06,2.53,11.53a.75.75,0,0,1-1.06-1.06l6-6a.75.75,0,0,1,1.06,0l6,6a.75.75,0,0,1,0,1.06A.74.74,0,0,1,14,11.75Z"></path>
                </svg>
            </div>
        </div>

        <div class="event-ray-file__body" v-if="hasSnippet && !collapsed">
            <div class="event-ray-file__snippet" v-for="(line, i) in file.snippet" :class="{'bg-pink-800 text-white': file.line_number == line.line_number}">
                <div class="w-12">{{ line.line_number }}.</div>
                <pre>{{ line.text }}</pre>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        file: Object,
        collapsed: {
            type: Boolean,
            default: true
        }
    },
    computed: {
        hasSnippet() {
            return this.file.snippet && this.file.snippet.length > 0
        }
    }
}
</script>

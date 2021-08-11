<template>
    <div ref="trace">
        <div class="font-semibold border-b flex items-end justify-between" v-for="(file, i) in payload.content.frames">
            <div class="flex gap-2 items-end">
                <div class="text-sm text-gray-400 w-10 text-right">{{ payload.content.frames.length - i }}</div>
                <a
                    class="text-blue-400 underline"
                    :href='`phpstorm://open?file=${encodeURIComponent(file.file_name)}&line=${file.line_number}`'>
                    {{ file.class || 'null' }}:{{ file.method }}
                </a>
            </div>
            <span class="text-sm text-gray-400">[line {{ file.line_number }}]</span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        payload: Object
    }
}
</script>

<template>
    <div ref="trace">
        <div class="p-3 bg-gray-100 border border-gray-200 rounded-t">
            <h3 class="text-gray-800 mb-1">
                <code class="font-semibold">{{ payload.content.class }}</code>
            </h3>
            <div class="text-gray-600 text-sm break-all">
                {{ payload.content.message }}
            </div>
        </div>
        <div class="bg-gray-50 border border-gray-200 py-2 flex-col justify-center space-y-2">
            <div class="font-semibold text-sm border-b flex justify-between px-3" v-for="(file, i) in payload.content.frames">
                <div class="flex gap-2 items-end">
                    <div class="text-gray-400 w-10 text-right">{{ payload.content.frames.length - i }}</div>
                    <a
                        class="text-blue-400 underline break-all"
                        :href='`phpstorm://open?file=${encodeURIComponent(file.file_name)}&line=${file.line_number}`'>
                        {{ file.class || 'null' }}:{{ file.method }}
                    </a>
                </div>
                <span class="text-gray-400 w-15">[{{ file.line_number }}]</span>
            </div>
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

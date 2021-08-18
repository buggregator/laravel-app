<template>
    <div class="text-xs cursor-pointer" @click="collapsed = !collapsed">
        <div class="bg-gray-50 text-gray-700 break-all font-semibold p-1 px-3 flex justify-between">
            <div>
                {{ file.file_name }}
                <span v-if="file.function" class="text-gray-400">in</span>
                {{ file.class || 'null' }}:{{ file.method }}
                <span v-if="file.function" class="text-gray-400">at line</span>
                {{ file.line_number }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        file: Object
    }
}
</script>

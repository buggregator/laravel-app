<template>
    <span class="border border-gray-300 bg-white text-gray-500 py-1 px-3 rounded-sm text-xs inline-block">
        {{ text }}
    </span>
</template>

<script>
export default {
    props: {
        text: String
    }
}
</script>

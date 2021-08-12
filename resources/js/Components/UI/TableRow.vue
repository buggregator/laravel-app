<template>
    <div class="md:flex bg-gray-50 border-b border-gray-200">
        <div class="px-4 md:py-2 py-1 text-left" v-if="title !== null" v-html="title"></div>
        <div class="px-4 md:py-2 py-1 break-words"><slot></slot></div>
    </div>
</template>

<script>
export default {
    props: {
        title: String
    }
}
</script>

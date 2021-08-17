<template>
    <div class="md:flex bg-gray-50 border-b border-gray-200 text-xs md:text-sm sm:space-y-1 py-2 px-4 items-center">
        <div class="font-bold mr-5 md:w-1/4" v-if="title !== null"><span v-html="title"></span></div>
        <div class="break-all md:w-3/4">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: String
    }
}
</script>

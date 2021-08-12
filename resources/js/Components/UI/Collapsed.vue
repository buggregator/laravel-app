<template>
    <div class="p-3 border border-gray-200 rounded bg-gray-100" :class="{'cursor-pointer': !isOpen}" @click="isOpen = !isOpen">
        <h3 class="text-gray-800 font-semibold flex items-center">
            {{ title }}

            <div class="w-5 h-5 ml-2">
                <svg
                    :class="{'transform rotate-180': isOpen, 'animate-bounce': !isOpen}"
                    height="100%" width="100%" fill="none" stroke="#606F7B"
                    stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </div>
        </h3>

        <div v-if="isOpen" class="mt-3">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        isOpen: {
            type: Boolean,
            default: false
        }
    }
}
</script>

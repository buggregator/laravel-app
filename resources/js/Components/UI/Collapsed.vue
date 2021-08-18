<template>
    <div class="border rounded bg-white" :class="{'cursor-pointer': !isOpen}" @click="isOpen = !isOpen">
        <h3 class="text-gray-800 font-semibold flex justify-between items-center border-b p-3">
            {{ title }}

            <div class="w-5 h-5 border border-gray-300 bg-white py-1 rounded">
                <svg viewBox="0 0 16 16"
                     fill="currentColor"
                     height="100%" width="100%"
                     :class="{'transform rotate-180': isOpen}"
                >
                    <path d="M14,11.75a.74.74,0,0,1-.53-.22L8,6.06,2.53,11.53a.75.75,0,0,1-1.06-1.06l6-6a.75.75,0,0,1,1.06,0l6,6a.75.75,0,0,1,0,1.06A.74.74,0,0,1,14,11.75Z"></path>
                </svg>
            </div>
        </h3>

        <div v-if="isOpen">
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

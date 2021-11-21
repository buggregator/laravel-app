<template>
    <div class="flex">
        <button :disabled="disabled" @click="continueExecution"
                class="px-5 py-2 flex items-center space-x-3 bg-gray-100 rounded-l-full border text-sm font-medium hover:bg-gray-50 active:bg-grey-300 focus:outline-none disabled:opacity-50">
            <div class="w-3 h-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 20 20">
                    <path fill="green" fill-rule="evenodd" d="M16.75 10.83L4.55 19A1 1 0 0 1 3 18.13V1.87A1 1 0 0 1 4.55 1l12.2 8.13a1 1 0 0 1 0 1.7z"/>
                </svg>
            </div>

            <span>Continue</span>
        </button>
        <button :disabled="disabled" @click="stopExecution"
                class="px-5 py-2 flex items-center space-x-3 bg-gray-100 border border-l-0 rounded-r-full text-sm font-medium hover:bg-gray-50 active:bg-grey-300 focus:outline-none disabled:opacity-50">
            <div class="w-3 h-3 bg-red-700"></div>
            <span>Stop execution</span>
        </button>
    </div>
</template>

<script>
export default {
    props: {
        payload: Object,
        disabled: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        continueExecution() {
            this.$emit('disable')
            axios.delete(route('ray.lock.delete', this.hash))
        },
        stopExecution() {
            this.$emit('disable')
            axios.delete(route('ray.lock.delete', this.hash), {
                params: {stop_execution: true}
            })
        }
    },
    computed: {
        hash() {
            return this.payload.content.name
        }
    }
}
</script>

<template>
    <div class="event-ray__pause">
        <button :disabled="disabled" @click="continueExecution"
                class="event-ray__pause-btn event-ray__pause-btn--continue active:bg-grey-300">
            <span class="w-3 h-3 block">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 20 20">
                    <path fill="green" fill-rule="evenodd" d="M16.75 10.83L4.55 19A1 1 0 0 1 3 18.13V1.87A1 1 0 0 1 4.55 1l12.2 8.13a1 1 0 0 1 0 1.7z"/>
                </svg>
            </span>

            <span>Continue</span>
        </button>
        <button :disabled="disabled" @click="stopExecution"
                class="event-ray__pause-btn event-ray__pause-btn--stop active:bg-grey-300">
            <span class="w-3 h-3 bg-red-700 block"></span>
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

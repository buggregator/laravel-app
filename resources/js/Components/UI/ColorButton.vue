<template>
    <button
        @click="toggleColor"
        class="h-3 w-3 md:w-4 md:h-4 rounded-full border-2"
        :class="styles"
    >
    </button>
</template>

<script>
import {computed} from 'vue';
import {useStore} from "vuex";

export default {
    props: {
        color: String
    },
    methods: {
        toggleColor() {
            this.store.commit('selectColor', this.color)
        }
    },
    computed: {
        selected() {
            return this.selectedColors.includes(this.color)
        },
        styles() {
            if (this.selected) {
                return `bg-${this.color}-700 border-none`
            }

            return `border-${this.color}-700`
        }
    },
    setup() {
        const store = useStore();

        let selectedColors = computed(function () {
            return store.state.selectedColors
        });

        return {
            store, selectedColors
        }
    }
}
</script>

<style scoped>

</style>

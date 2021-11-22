<template>
    <div class="flex flex-row flex-wrap items-center gap-2">
        <button v-if="hasSelectedColors" class="w-4 h-4 text-red-700 -ml-6" @click="clear">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><g id="close"><path id="x" d="M18.717 6.697l-1.414-1.414-5.303 5.303-5.303-5.303-1.414 1.414 5.303 5.303-5.303 5.303 1.414 1.414 5.303-5.303 5.303 5.303 1.414-1.414-5.303-5.303z"/></g></svg>
        </button>
        <div v-else class="w-4 h-4 -ml-6"></div>
        <ColorButton v-for="color in colors" :color="color"/>
    </div>
</template>

<script>
import {computed} from 'vue';
import {useStore} from "vuex";
import ColorButton from "../UI/ColorButton";

export default {
    components: {ColorButton},
    methods: {
        clear() {
            this.store.commit('clearSelectedColors')
        }
    },
    setup() {
        const store = useStore();

        const colors = computed(()  => store.state.availableColors)
        const hasSelectedColors = computed(()  => store.state.selectedColors.length > 0)

        return {
            store, colors, hasSelectedColors
        }
    }
}
</script>

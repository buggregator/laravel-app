<template>
    <div class="flex flex-row flex-wrap gap-2">
        <button v-if="hasSelectedColors" class="w-3 h-3 md:w-4 md:h-4 text-red-700" @click="clear">
            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 24 24" fill="currentColor">
                <g id="close">
                    <path id="x"
                          d="M18.717 6.697l-1.414-1.414-5.303 5.303-5.303-5.303-1.414 1.414 5.303 5.303-5.303 5.303 1.414 1.414 5.303-5.303 5.303 5.303 1.414-1.414-5.303-5.303z"/>
                </g>
            </svg>
        </button>

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

        let colors = computed(function () {
            return store.state.availableColors
        });

        let hasSelectedColors = computed(function () {
            return store.state.selectedColors.length > 0
        });

        return {
            store, colors, hasSelectedColors
        }
    }
}
</script>

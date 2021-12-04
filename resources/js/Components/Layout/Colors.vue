<template>
    <div class="filters__colors">
        <button v-if="hasSelectedColors" class="filters__btn-clear" @click="clear">
            <TimesIcon />
        </button>
        <div v-else class="w-4 h-4 -ml-6"></div>
        <ColorButton v-for="color in colors" :color="color"/>
    </div>
</template>

<script>
import {computed} from 'vue';
import {useStore} from "vuex";
import ColorButton from "../UI/ColorButton";
import TimesIcon from "../UI/Icons/TimesIcon";

export default {
    components: {TimesIcon, ColorButton},
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

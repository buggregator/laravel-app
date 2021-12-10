<template>
    <div class="screens">
        <h3 class="screens__title" v-if="currentScreen">
            <span class="text-xs">{{ currentScreen }}</span>
        </h3>

        <nav class="screens__nav">
            <template v-for="screen in screens">
                <WsConnectionIcon class="screens__screen-current" v-if="currentScreen == screen"/>
                <div v-else class="screens__screen" @click="switchScreen(screen)">
                    <ScreenIcon />
                </div>
            </template>

            <button @click="newScreen" class="screens__btn-new">
                <NewScreenIcon />
            </button>
        </nav>

        <button @click="clearEvents" class="screens__btn-clear">
            Clear screen
        </button>
    </div>
</template>

<script>
import {computed} from 'vue';
import {useStore} from "vuex";
import WsConnectionIcon from "../UI/WsConnectionIcon";
import NewScreenIcon from "../UI/Icons/NewScreenIcon";
import ScreenIcon from "../UI/Icons/ScreenIcon";

export default {
    components: {ScreenIcon, NewScreenIcon, WsConnectionIcon},
    methods: {
        newScreen() {
            this.store.commit('switchScreen');
        },
        switchScreen(screen) {
            this.store.commit('switchScreen', screen)
        },
        clearEvents() {
            this.store.dispatch('clearEvents')
        }
    },

    setup() {
        const store = useStore();

        const screens = computed(() => store.getters.screens)
        const currentScreen = computed(() => store.state.currentScreen)
        const totalEvents = computed(() => store.getters.totalEvents)

        return {
            store, screens, currentScreen, totalEvents
        }
    }
}
</script>

<template>
    <div class="flex p-4 justify-between items-center bg-gray-100 border-b border-gray-200">
        <h3 class="text-gray-700 font-bold" v-if="currentScreen">{{ currentScreen }}</h3>
        <div class="flex gap-2 justify-center">
            <div v-for="screen in screens"
                 class="w-3 h-3 cursor-pointer rounded-full border border-blue-400"
                 :class="{'bg-blue-500': currentScreen == screen}"
                 @click="switchScreen(screen)"></div>
        </div>
        <div>
            <button
                @click="clearEvents"
                class="px-3 py-1 text-sm border border-gray-300 text-gray-400 rounded-sm hover:border-gray-500 hover:text-gray-600"
            >
                Clear
            </button>
        </div>
    </div>
</template>

<script>
import {computed} from 'vue';
import {useStore} from "vuex";
export default {
    methods: {
        switchScreen(screen) {
            this.store.commit('switchScreen', screen);
        },
        clearEvents() {
            this.store.commit('clearLogs');
        }
    },

    setup() {
        const store = useStore();

        let screens = computed(function () {
            return store.state.screens
        });

        let currentScreen = computed(function () {
            return store.state.currentScreen
        });

        return {
            store, screens, currentScreen
        }
    }
}
</script>

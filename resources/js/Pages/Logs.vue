<template>
    <Head title="Ray server"/>

    <div>
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
                    @click="clearLogs"
                    class="px-3 py-1 text-sm border border-gray-300 text-gray-400 rounded-sm hover:border-gray-500 hover:text-gray-600"
                >
                    Clear
                </button>
            </div>
        </div>
        <LogItem :log=log v-for="log in logs"/>
    </div>
</template>

<script>
import {computed} from 'vue';
import {useStore} from "vuex";
import LogItem from "../Components/Log";

import {Head, Link} from '@inertiajs/inertia-vue3';

export default {
    components: {
        Head, Link, LogItem
    },

    methods: {
        switchScreen(screen) {
            this.store.commit('switchScreen', screen);
        },
        clearLogs() {
            this.store.commit('clearLogs');
        }
    },

    setup() {
        const store = useStore();

        let logs = computed(function () {
            return store.state.logs[store.state.currentScreen] || []
        });

        let screens = computed(function () {
            return store.state.screens
        });

        let currentScreen = computed(function () {
            return store.state.currentScreen
        });

        return {
            store, logs, screens, currentScreen
        }
    }
}
</script>

<template>
    <div>
        <svg v-if="wsConnected" class="animate-spin text-white" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="#3b82f6" stroke-width="4"></circle>
            <path class="opacity-75" fill="#3b82f6" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 478.125 478.125">
            <path fill="red" d="M201.654 127.525h-31.9c-10.557 0-19.125 8.645-19.125 19.125v184.9c0 10.558 8.645 19.125 19.125 19.125h31.9c10.557 0 19.125-8.645 19.125-19.125v-184.9c0-10.556-8.568-19.125-19.125-19.125zM308.448 127.525h-31.9c-10.558 0-19.125 8.645-19.125 19.125v184.9c0 10.558 8.645 19.125 19.125 19.125h31.9c10.557 0 19.125-8.645 19.125-19.125v-184.9c0-10.556-8.644-19.125-19.125-19.125z"/>
            <path fill="red" d="M239.062 0C107.023 0 0 107.023 0 239.062s107.023 239.062 239.062 239.062 239.062-107.023 239.062-239.062S371.102 0 239.062 0zm.23 409.811c-94.171 0-170.519-76.424-170.519-170.519S145.197 68.773 239.292 68.773s170.519 76.424 170.519 170.519-76.271 170.519-170.519 170.519z"/>
        </svg>
    </div>
</template>

<script>
import {computed} from 'vue';
import {useStore} from "vuex";

export default {
    setup() {
        const store = useStore();

        let wsConnected = computed(function () {
            return store.state.ws.connected
        });

        return {
            wsConnected
        }
    }
}
</script>

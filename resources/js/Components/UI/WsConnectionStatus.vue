<template>
    <div class="flex space-x-3 items-center">
        <WsConnectionIcon class="h-8 w-8" />
        <span v-if="wsConnected">Hurry! Give me something. I can't wait to show it.</span>
        <span v-else>Houston we have a problem! Websocket is not connected. Try to reload page.</span>
    </div>
</template>

<script>
import {computed} from 'vue';
import {useStore} from "vuex";
import WsConnectionIcon from "./WsConnectionIcon";

export default {
    components: {WsConnectionIcon},
    setup() {
        const store = useStore();

        let wsConnected = computed(function () {
            return store.state.wsConnected
        });

        return {
            wsConnected
        }
    }
}
</script>

<template>
    <div class="ws-connection">
        <WsConnectionIcon class="ws-connection__icon" />
        <div class="ws-connection__text">
            <span v-if="wsConnected">Hurry! Give me something. I can't wait to show it.</span>
            <span v-else>Houston we have a problem! Websocket is not connected. Try to reload page.</span>
        </div>
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

        let wsConnected = computed(()  => store.state.ws.connected)

        return {
            wsConnected
        }
    }
}
</script>

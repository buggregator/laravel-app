<template>
    <div class="flex flex-col text-center md:flex-row gap-4 items-center justify-center">
        <WsConnectionIcon class="h-8 w-8" />
        <div class="text-sm md:text-base text-gray-600 font-semibold">
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

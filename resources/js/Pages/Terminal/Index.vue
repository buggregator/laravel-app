<template>
    <MainLayout title="Terminal">
        <main class="flex flex-grow w-full">
            <div class="w-full h-full" id="terminal"></div>
        </main>
    </MainLayout>
</template>

<script>
import MainLayout from "@/Components/Layout/Main"
import {Terminal} from 'xterm'
import {FitAddon} from 'xterm-addon-fit'
import {useStore} from "vuex";
import {computed} from "vue";

export default {
    components: {
        MainLayout
    },
    setup() {
        const store = useStore();
        const messages = computed(() => store.state.terminal.messages)

        return {
            messages, store
        }
    },
    destroyed() {
        this.term.clear()
    },
    mounted() {
        if (!this.terminalInit) {
            this.term = new Terminal()

            const fitAddon = new FitAddon()
            this.term.loadAddon(fitAddon)

            this.term.open(document.getElementById('terminal'))
            fitAddon.fit()

            ws.listen('terminal', 'Websocket\\TerminalWrite', (payload) => {
                payload.payload.message.split("\n").forEach((line) => this.term.writeln(line))
            })

            this.terminalInit = true;
        }

        this.messages.forEach(msg => this.term.writeln(msg))

    },
}
</script>

<style>
.xterm {
    height: 100%;
    padding: 15px;
    overflow: hidden;
}

.xterm-viewport {
    width: 100% !important;
}
</style>

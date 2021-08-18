<template>
    <div ref="trace">
        <ssh-pre language="json">
            {{ json }}
        </ssh-pre>
    </div>
</template>

<script>
import SshPre from 'simple-syntax-highlighter'

export default {
    components: {SshPre},
    props: {
        payload: Object
    },
    computed: {
        json() {
            let json = this.payload.content.value;

            if (typeof json == 'string') {
                json = JSON.parse(json)
            }

            return JSON.stringify(json, null, 2)
        }
    }
}
</script>

<style scoped>

</style>

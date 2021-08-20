<template>
    <div>
        <ssh-pre language="json">
            {{ json }}
        </ssh-pre>
    </div>
</template>

<script>
import SshPre from 'simple-syntax-highlighter'

export default {
    components: {SshPre},
    name: "Default",
    props: {
        payload: Object
    },
    computed: {
        json() {
            return JSON.stringify(this.payload, null, 2)
        }
    }
}
</script>

<style scoped>

</style>

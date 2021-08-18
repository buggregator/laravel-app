<template>
    <ssh-pre>
        {{ payload.content.value }}
    </ssh-pre>
</template>

<script>
import SshPre from 'simple-syntax-highlighter'
export default {
    components: {SshPre},
    props: {
        payload: Object
    }
}
</script>

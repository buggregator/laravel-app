<template>
<Exception v-for="payload in event.payloads" :payload="payload" />
</template>

<script>

import Exception from "@/Components/Sentry/Payloads/Exception"
export default {
    components: {
        Exception
    },
    props: {
        event: Object
    }
}
</script>

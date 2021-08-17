<template>
    <Event :event="event">
        <Exception v-for="e in event.payloads" :payload="e" />
    </Event>
</template>

<script>
import Event from "../Event";
import Exception from "./Payloads/Exception";

export default {
    components: {Event, Exception},
    props: {
        event: Object
    }
}
</script>

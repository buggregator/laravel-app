<template>
    <Event :event="event">
        <Exception v-for="e in event.payloads" :payload="e" />
        <Host :name="event.serverName" />
    </Event>
</template>

<script>
import Host from "@/Components/UI/Host";
import Event from "../Event";
import Exception from "./Payloads/Exception";

export default {
    components: {Event, Exception, Host},
    props: {
        event: Object
    }
}
</script>

<template>
    <Event :event="event">
        <Exception :event="event" />
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
    },
    mounted() {
        console.log(this.event)
    }
}
</script>

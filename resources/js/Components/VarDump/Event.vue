<template>
    <Event :event="event">
        <Dump :value="event.payloads.value" />

        <Origin :origin="event.origin" />
    </Event>
</template>

<script>
import Dump from "@/Components/UI/Dump";
import Event from "../Event";
import Origin from "../Ray/Origin";

export default {
    components: {Origin, Event, Dump},
    props: {
        event: Object,
    }
}
</script>

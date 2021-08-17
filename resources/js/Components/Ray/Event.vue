<template>
    <Event :event="event">
        <EventPayload
            v-for="payload in event.payloads"
            :payload="payload"
            :disabled="event.disabled"
            v-on:disable="event.disable()"
            v-on:delete="$emit('deleteEvent')"
        />
    </Event>
</template>

<script>
import EventPayload from "./Payload";
import Event from "../Event";

export default {
    components: {Event, EventPayload},
    props: {
        event: Object
    }
}
</script>

<template>
    <Event :event="event" class="event--ray">
        <div class="event-ray__wrap">
            <EventPayload
                v-for="payload in event.payloads"
                :payload="payload"
                :id="event.id"
                :disabled="event.disabled"
                v-on:disable="event.disable()"
                v-on:delete="$emit('deleteEvent')"
            />
        </div>
        <div class="event-ray__json">
            <JsonChip :href="event.route.json" />
        </div>
        <Origin v-if="hasPayloads" class="mt-3" :origin="event.payloads[0].origin"/>
    </Event>
</template>

<script>
import Origin from "./Origin";
import EventPayload from "./Payload";
import Event from "../Event";
import JsonChip from "@/Components/UI/JsonChip";

export default {
    components: {JsonChip, Event, EventPayload, Origin},
    props: {
        event: Object
    },
    computed: {
        hasPayloads() {
            return this.event.payloads.length > 0
        }
    }
}
</script>

<template>
    <Event :event="event" class="event--smtp">
        <NavItem :event="event" class="event-smtp__nav-item"/>
        <div class="event-smtp__json">
            <JsonChip :href="event.route.json" />
        </div>
    </Event>
</template>

<script>
import Event from "../Event";
import NavItem from "./NavItem";
import JsonChip from "@/Components/UI/JsonChip";

export default {
    components: {
        JsonChip,
        Event, NavItem
    },
    props: {
        event: Object
    }
}
</script>

<template>
    <Event :event="event">
        <NavItem :event="event" />
    </Event>
</template>

<script>
import Event from "../Event";
import NavItem from "./NavItem";

export default {
    components: {
        Event, NavItem
    },
    props: {
        event: Object
    }
}
</script>

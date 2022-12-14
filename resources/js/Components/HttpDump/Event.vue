<template>
    <Event :event="event" class="event--httpdump">
        <Link as="div" :href="event.route.show" class="link flex-grow">
            <p>{{ event.event.request.method }} {{ event.event.request.uri }}</p>
        </Link>
    </Event>
</template>

<script>
import Event from "../Event";
import {Link} from '@inertiajs/inertia-vue3'

export default {
    components: {
        Link, Event
    },
    props: {
        event: Object
    }
}
</script>

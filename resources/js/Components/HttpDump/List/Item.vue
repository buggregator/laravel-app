<template>
    <div class="relative">
        <Link as="div" :href="event.route.show" class="link flex-grow">
            <p>{{ event.event.request.method }} {{ event.event.request.uri }}</p>
            <span class="text-xs"> {{ event.date }}</span>
        </Link>
    </div>
</template>

<script>
import {Link} from '@inertiajs/inertia-vue3'

export default {
    components: { Link },
    props: {
        event: Object
    }
}
</script>

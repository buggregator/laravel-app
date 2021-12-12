<template>
    <Link as="div" :href="event.route.show" class="sentry-item">
        <div class="sentry-item__header">
            <h3 class="sentry-item__title">
                {{ event.payload.type }}
            </h3>

            <span v-if="event.location" class="sentry-item__location">
                <strong>{{ location.filename }}</strong> in <strong>{{ location.function }}</strong>
            </span>
        </div>

        <div class="sentry-item__value">
            {{ event.payload.value }}
        </div>

        <div class="sentry-item__text">
            <div>
                <span>{{ date }}</span>
                <span class="mx-2">|</span>
                <span><strong>logger: </strong>{{ event.logger }}</span>
                <span class="mx-2">|</span>
                <span><strong>env: </strong>{{ event.environment }}</span>
            </div>
            <Host :name="event.serverName" class="sentry-item__host"/>
        </div>
    </Link>
</template>

<script>
import Host from "@/Components/UI/Host";
import {Link} from '@inertiajs/inertia-vue3'

export default {
    components: {
        Link, Host
    },
    props: {
        event: Object
    },
    computed: {
        date() {
            return this.event.date.fromNow()
        },
        location() {
            return this.event.location
        }
    }
}
</script>

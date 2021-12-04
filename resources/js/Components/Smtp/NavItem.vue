<template>
    <Link :href="event.route.show"
          class="event-smtp__link"
          :class="{'active': isActive }"
    >

        <div class="event-sentry__left" :class="{'active': isActive }"></div>
        <div class="event-smtp__link-body">
            <h3 class="event-smtp__link-title" :class="{ 'font-bold': isActive }">{{ event.event.subject }}</h3>
            <div class="event-smtp__link-text">
                <span>
                    <strong>To:</strong> {{ event.event.to[0].email }}
                </span>
                <span>{{ date }}</span>
            </div>
        </div>

    </Link>
</template>

<script>
import {Link} from '@inertiajs/inertia-vue3'

export default {
    components: {Link},
    props: {
        event: Object
    },
    computed: {
        url() {
            return this.event.route.show
        },
        date() {
            return this.event.date.fromNow()
        },
        isActive() {
            const url = new URL(this.url)
            return this.$page.url.startsWith(url.pathname)
        }
    }
}
</script>

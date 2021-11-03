<template>
    <Link :href="`/mail/${event.uuid}`"
          class="block border-b text-sm hover:bg-white flex items-stretch"
          :class="{ 'bg-white': isActive, 'bg-gray-50': !isActive }"
    >

        <div class="w-1 self-stretch flex-none rounded-r" :class="{ 'bg-blue-500': isActive }"></div>
        <div class="flex-grow p-3">
            <h3 class="font-semibold" :class="{ 'font-bold': isActive }">{{ event.event.subject }}</h3>
            <div class="flex justify-between text-xs text-gray-500">
                <span>
                    to: {{ event.event.to[0].email }}
                </span>
                <span>
                    {{ date }}
                </span>
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
            return `/mail/${this.event.uuid}`
        },
        date() {
            return this.event.date.format('HH:mm:ss')
        },
        isActive() {
            return this.$page.url.startsWith(this.url)
        }
    }
}
</script>

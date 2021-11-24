<template>
    <Link :href="event.route.show"
          class="block border-b text-sm hover:bg-white flex items-stretch"
          :class="{'bg-purple-50': isActive }"
    >

        <div class="w-1 self-stretch flex-none rounded-r" :class="{ 'bg-blue-500': isActive }"></div>
        <div class="flex-grow p-3">
            <h3 class="font-semibold" :class="{ 'font-bold': isActive }">{{ event.event.subject }}</h3>
            <div class="flex justify-between text-xs text-gray-500">
                <span><strong>To:</strong> {{ event.event.to[0].email }}</span>
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

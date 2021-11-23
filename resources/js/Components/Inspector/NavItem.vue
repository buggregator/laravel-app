<template>
    <Link :href="`/inspector/${event.uuid}`"
          class="block border-b  rounded text-sm hover:bg-white flex items-stretch nav-item"
    >
        <div class="flex-grow flex mt-0 justify-between p-3 flex-wrap nav-item__list">
            <div class="pr-3 w-1/5 nav-item__item">
                <span class="text-gray-500 text-xs" >Duration</span><br>{{event.process.duration}} ms
            </div>
            <div class="pr-3 w-1/5 nav-item__item">
                <span class="text-gray-500 text-xs">Memory</span><br>{{event.process.memory_peak}} MB
            </div>
            <div class="pr-3 w-1/5 overflow-ellipsis overflow-hidden nav-item__item"><span class="text-gray-500 text-xs">Name</span><br>{{event.process.name}}</div>
            <div class="pr-3 w-1/5 nav-item__item"><span class="text-gray-500 text-xs">Hostname</span><br>{{event.serverName}}</div>
            <div class="pr-3 text-white nav-item__item"><span class="text-gray-500 text-xs">Result</span><br>
                <span :class="`bg-${event.color}-700`" class="p-1 rounded text-2xs">{{event.processResult}}</span>
            </div>
        </div>
    </Link>
</template>

<script>
import {Link} from '@inertiajs/inertia-vue3'

export default {
    components: {Link},
    props: {
        event: Object,
    },
    computed: {}
}
</script>
<style lang="css">
/* стили перенести после мержда */
.nav-item--sidebar .nav-item__list {
    width: 100%;
}
.nav-item--sidebar .nav-item__item {
    width: auto !important;
    min-width: 50%;
    max-width: 100%;
}
</style>

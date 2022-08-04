<template>
    <div>
        <h1 class="event-inspector__title">
            <span>{{ event.process.name }}</span>
            <JsonChip :href="event.route.json" class="ml-auto"/>
        </h1>

        <Link :href="event.route.show" class="event-inspector__link">
            <Cards :event="event" class="event-inspector__cards"/>
        </Link>

        <div class="event-inspector__footer">
            <span>{{ date }}</span>
            <Host :name="event.serverName" class="event-inspector__host"/>
        </div>
    </div>
</template>

<script>
import Host from "@/Components/UI/Host";
import {Link} from '@inertiajs/inertia-vue3'
import Cards from "./Show/Cards";
import JsonChip from "@/Components/UI/JsonChip";

export default {
    components: {JsonChip, Cards, Link, Host},
    props: {
        event: Object,
    },
    computed: {
        date() {
            return this.event.date.fromNow()
        },
    }
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

<template>
   <div>
       <h1 class="font-bold mb-2">
           {{ event.process.name }}
       </h1>

       <Link :href="event.route.show" class="block">
           <Cards :event="event" class="border hover:bg-white" />
       </Link>
   </div>
</template>

<script>
import {Link} from '@inertiajs/inertia-vue3'
import Cards from "./Show/Cards";

export default {
    components: {Cards, Link},
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

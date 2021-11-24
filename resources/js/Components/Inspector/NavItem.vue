<template>
  <div>
    <h1 class="font-semibold text-blue-800 mb-2">
      {{ event.process.name }}
    </h1>

    <Link :href="event.route.show" class="block">
      <Cards :event="event" class="border hover:bg-white"/>
    </Link>

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-3 text-xs text-gray-400">
      <span>{{ date }}</span>
      <Host :name="event.serverName"/>
    </div>
  </div>
</template>

<script>
import Host from "@/Components/UI/Host";
import {Link} from '@inertiajs/inertia-vue3'
import Cards from "./Show/Cards";

export default {
  components: {Cards, Link, Host},
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

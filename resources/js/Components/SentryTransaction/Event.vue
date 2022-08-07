<template>
  <Event :event="event" class="event--sentry">
    <div class="event-sentry__wrap">
      <div>
        <span>{{ date }}</span>
        <span class="mx-2">|</span>
        <span><strong>logger: </strong>{{ event.logger }}</span>
        <span class="mx-2">|</span>
        <span><strong>env: </strong>{{ event.environment }}</span>
      </div>
      <Host :name="event.serverName" class="event-sentry__host"/>
    </div>
  </Event>
</template>

<script>
import Host from "@/Components/UI/Host";
import Event from "../Event";

export default {
  components: {Event, Host},
  props: {
    event: Object
  },
  computed: {
    date() {
      return this.event.date.fromNow()
    },
  }
}
</script>

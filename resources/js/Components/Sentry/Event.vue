<template>
  <Event :event="event">
    <Exception :event="event"/>

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-3 text-xs text-gray-400">
      <div>
        <span>{{ date }}</span>
        <span class="mx-2">|</span>
        <span><strong>logger: </strong>{{ event.logger }}</span>
        <span class="mx-2">|</span>
        <span><strong>env: </strong>{{ event.environment }}</span>
      </div>
      <Host :name="event.serverName"/>
    </div>
  </Event>
</template>

<script>
import Host from "@/Components/UI/Host";
import Event from "../Event";
import Exception from "./Payloads/Exception";

export default {
  components: {Event, Exception, Host},
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

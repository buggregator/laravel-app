<template>
  <div ref="trace">
    <Dump v-if="payload.content.class_based_event" :value="payload.content.event" />
    <div v-else>
      <h3 class="text-gray-800">Event: <span class="font-semibold">{{ payload.content.name }}</span></h3>
      <Dump :value="payload.content.payload" />
    </div>
  </div>
</template>

<script>
import Dump from "./Dump";
export default {
  components: {Dump},
  props: {
    payload: Object
  },
  computed: {}
}
</script>

<template>
  <div>
    <Dump v-if="payload.content.class_based_event" :value="payload.content.event" />
    <div v-else>
      <h3>
          Event: <code class="font-semibold">{{ payload.content.name }}</code>
      </h3>
      <Dump :value="payload.content.payload" />
    </div>
  </div>
</template>

<script>
import Dump from "@/Components/UI/Dump";
export default {
  components: {Dump},
  props: {
    payload: Object
  },
  computed: {}
}
</script>

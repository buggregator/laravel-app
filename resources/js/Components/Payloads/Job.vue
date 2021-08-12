<template>
  <div ref="trace">
      <Dump :value="payload.content.job" />
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

<template>
  <div>
      <Dump :value="payload.content.job" />
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

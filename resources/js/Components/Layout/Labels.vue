<template>
    <section class="event-type-filters">
        <Label @click="toggleLabel(label)"
               v-for="label in labels"
               class="filter-label"
               :class="{'active': selectedLabels.includes(label)}"
        >
            {{ label }}
        </Label>
    </section>
</template>

<script>
import Label from "../UI/Label";
import {computed} from 'vue';
import {useStore} from "vuex";
export default {
    components: {Label},

    methods: {
        toggleLabel(label) {
            this.store.commit('selectLabel', label)
        }
    },

    computed: {
        hasLabels() {
            return this.labels.length > 0
        }
    },

    setup() {
        const store = useStore();


        const labels = computed(()  => store.getters.availableLabels)
        const selectedLabels = computed(()  => store.state.selectedLabels)

        return {
            store, labels, selectedLabels
        }
    }
}
</script>

<template>
    <Tab v-slot="{ selected }" as="template">
        <div class="flex flex-col">
            <button class="px-4 py-2 font-bold text-sm" :class="{'text-gray-600': !selected}">
                <slot></slot>
            </button>
            <div class="h-1 rounded-t" :class="{'bg-blue-500': selected}"></div>
        </div>
    </Tab>
</template>

<script>
import {Tab} from '@headlessui/vue'

export default {
    components: {Tab}
}
</script>

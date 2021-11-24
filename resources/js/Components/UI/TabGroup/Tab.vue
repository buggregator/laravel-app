<template>
    <Tab v-slot="{ selected }" as="template">
        <div class="flex flex-col">
            <button class="px-4 py-2 font-bold text-sm" :class="{'text-muted': !selected}">
                <slot></slot>
            </button>
            <div class="h-1 rounded-t" :class="{'bg-blue-500 dark:bg-blue-100': selected}"></div>
        </div>
    </Tab>
</template>

<script>
import {Tab} from '@headlessui/vue'

export default {
    components: {Tab}
}
</script>

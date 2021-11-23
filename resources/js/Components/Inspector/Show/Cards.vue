<template>
    <section class="py-5 px-4 md:px-6 lg:px-8 border-b flex flex-col sm:flex-row justify-between items-start divide-y sm:divide-y-0 sm:divide-x">
        <div class="flex flex-col w-full sm:w-1/4 pb-5 sm:pb-0">
            <span class="text-gray-500 font-bold text-2xs mb-1 uppercase">Timestamp</span>
            <strong class="text-xs sm:text-sm md:text-md">{{ event.processDate }}</strong>
        </div>
        <div class="flex flex-col w-full sm:w-1/4 sm:px-10 py-5 sm:py-0">
            <span class="text-gray-500 font-bold text-2xs mb-1 uppercase">Duration</span>
            <strong class="text-xs sm:text-sm md:text-md">{{ event.process.duration }} ms</strong>
        </div>
        <div class="flex flex-col w-full sm:w-1/4 sm:px-10 py-5 sm:py-0">
            <span class="text-gray-500 font-bold text-2xs mb-1 uppercase">Result</span>
            <span :class="`bg-${event.color}-700`" class="px-3 py-1 text-white rounded text-2xs font-bold">{{ event.processResult }}</span>
        </div>
        <div class="flex flex-col w-full sm:w-1/4 sm:pl-10 pt-5 sm:pt-0">
            <span class="text-gray-500 font-bold text-2xs mb-1 uppercase">Hostname</span>
            <span class="bg-gray-100 px-3 py-1 rounded text-xs font-bold">{{ event.serverName }}</span>
        </div>
    </section>
</template>

<script>
export default {
    props: ['event']
}
</script>

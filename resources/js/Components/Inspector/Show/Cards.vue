<template>
    <section class="pt-5 pb-4 px-4 md:px-5 border-b flex flex-col sm:flex-row justify-between items-start divide-y sm:divide-y-0 sm:divide-x">
        <div class="w-full sm:w-1/4 pb-5 sm:pb-0">
            <h4 class="text-gray-500 font-bold text-2xs mb-1 uppercase">Timestamp</h4>
            <strong class="text-xs sm:text-sm md:text-md">{{ event.processDate }}</strong>
        </div>
        <div class="w-full sm:w-1/4 sm:px-10 py-5 sm:py-0">
            <h4 class="text-gray-500 font-bold text-2xs mb-1 uppercase">Duration</h4>
            <strong class="text-xs sm:text-sm md:text-md">{{ event.process.duration }} ms</strong>
        </div>
        <div class="w-full sm:w-1/4 sm:px-10 py-5 sm:py-0">
            <h4 class="text-gray-500 font-bold text-2xs mb-1 uppercase">Result</h4>
            <span :class="`bg-${event.color}-700`" class="px-3 py-1 text-white rounded text-2xs font-bold">{{ event.processResult }}</span>
        </div>
    </section>
</template>

<script>
export default {
    props: ['event']
}
</script>

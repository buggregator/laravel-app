<template>
    <section class="py-5 px-4 md:px-6 lg:px-8 border-b">
        <h3 class="text-gray-400 font-bold uppercase text-sm mb-5">Timeline</h3>

        <div v-if="segmentTypes.length > 0" class="flex space-x-7 mb-4">
            <div v-for="type in segmentTypes" class="flex items-center">
                <div :class="type.color" class="w-4 h-4 rounded mr-2"></div>
                <span class="text-xs font-bold">{{ type.type }}</span>
            </div>
        </div>

        <div v-if="series.length > 0" class="overflow-x-scroll border border-gray-50">
            <div
                class="grid grid-cols-6 divide-x divide-gray-50 border-b border-gray-50 font-bold text-left text-2xs sm:text-xs md:text-sm">
                <div v-for="segment in grid.segments" class="py-2 pl-3">{{ segment }} ms</div>
            </div>
            <div
                :style="{'background-image': 'linear-gradient(to right, whitesmoke 1px, transparent 1px)', 'background-size': `${grid.widthPercent}% 20%`}">
                <div v-for="row in series" class="my-2">
                    <div class="text-2xs md:text-xs font-bold text-gray-500 whitespace-nowrap"
                         :style="{'margin-left': row.marginPercent + '%'}">
                        {{ row.segment.label }} - {{ row.segment.duration }} ms
                    </div>
                    <div :style="{'margin-left': row.marginPercent + '%'}" class="-mb-3 sm:-mb-4 md:-mb-5">
                        <span class="-ml-14 text-2xs font-bold text-gray-200">{{ row.segment.start }} ms</span>
                    </div>
                    <div class="h-2 md:h-3 lg:h-4 rounded min-w-1" :class="[row.color]"
                         :style="{width: row.widthPercent + '%', 'margin-left': row.marginPercent + '%'}"></div>
                </div>
            </div>
        </div>
        <div v-else class="flex w-full flex-col items-center mt-5">
            <div class="w-1/5">
                <svg class="fill-current text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 114 40">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M47 .7A1 1 0 0 0 45 .6L36 19H1a1 1 0 1 0 0 2h35.5c.4 0 .7-.2.9-.6l8.4-16.7L49 14.5a4 4 0 0 0 2.3 7.5l5.2 17.3a1 1 0 0 0 1.9.2l9.2-15.8h29.9a1 1 0 1 0 0-2H67a1 1 0 0 0-.9.5l-8.3 14.3-4.6-15.2A4 4 0 0 0 51 14L47 .7Zm54.5 21a1 1 0 0 0 0 2h3.5a1 1 0 0 0 0-2h-3.5Zm8 0a1 1 0 0 0 0 2h3.5a1 1 0 0 0 0-2h-3.5Z"/>
                </svg>
            </div>
            <h3 class="text-lg md:text-xl lg:text-3xl mt-5 font-bold text-gray-300">No data</h3>
        </div>
    </section>
</template>

<script>
export default {
    props: {
        event: Object
    },
    data() {
        return {}
    },
    computed: {
        grid() {
            let duration = this.event.process.duration
            const totalCells = 5;
            const width = (duration / totalCells + 1);
            const widthPercent = (100 / (totalCells + 1)).toFixed(2);

            let segments = [duration];
            for (let i = 0; i < totalCells; i++) {
                let d = Math.abs(duration -= width)
                segments.push(Math.floor(d))
            }

            return {
                segments: segments.reverse(),
                width,
                widthPercent
            }
        },
        segmentTypes() {
            return [...new Set(this.event.segments.map(data => data.type))].map(type => {
                return {color: `bg-${this.event.segmentColor(type)}-400`, type}
            })
        },
        series() {
            const duration = this.event.process.duration

            return this.event.segments.map(segment => {
                const widthPercent = Math.max((segment.duration * 100 / duration).toFixed(2), 0.5)
                const marginPercent = (segment.start * 100 / duration).toFixed(2)

                return {
                    widthPercent,
                    marginPercent,
                    segment,
                    color: `bg-${this.event.segmentColor(segment.type)}-400`
                }
            })
        }
    },
}
</script>

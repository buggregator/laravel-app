<template>
    <section class="py-5 px-4 md:px-6 lg:px-8 border-b">
        <h3 class="text-gray-400 font-bold uppercase text-sm mb-5">Timeline</h3>


        <div class="overflow-x-scroll border" :style="{'background-image': 'linear-gradient(to right, rgb(233 233 233) 1px, transparent 1px)', 'background-size': `${grid.widthPercent}% 20%`}">
            <div class="grid grid-cols-6 divide-x border-b font-bold text-right text-2xs sm:text-xs md:text-sm">
                <div v-for="segment in grid.segments" class="py-2 pr-3">{{ segment }}ms</div>
            </div>
            <div v-for="row in series" class="my-2">
                <div class="text-2xs md:text-xs font-bold text-gray-500 whitespace-nowrap" :style="{'margin-left': row.marginPercent + '%'}">
                    {{ row.segment.label }} - {{ row.segment.duration }} ms
                </div>
                <div class="h-2 md:h-3 lg:h-4 rounded min-w-1" :class="[row.color]" :style="{width: row.widthPercent + '%', 'margin-left': row.marginPercent + '%'}"></div>
            </div>
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
            const widthPercent = (100 / (totalCells + 1)).toFixed(3);
            const width = (duration / totalCells);

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
        series() {
            const duration = this.event.process.duration

            return this.event.segments.map(segment => {
                const widthPercent = Math.max((segment.duration * 100 / duration).toFixed(3), 0.3)
                const marginPercent = (segment.start * 100 / duration).toFixed(3)

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

<template>
    <section class="inspector-timeline">
        <h3 class="inspector-timeline__title">Timeline</h3>

        <div v-if="segmentTypes.length > 0" class="inspector-timeline__types">
            <div v-for="type in segmentTypes" class="inspector-timeline__types-item">
                <div :class="type.color" class="inspector-timeline__color"></div>
                <span class="inspector-timeline__type">{{ type.type }}</span>
            </div>
        </div>

        <div v-if="series.length > 0" class="inspector-timeline__chart">
            <div class="inspector-timeline__chart-y">
                <div v-for="segment in grid.segments" class="py-2 pl-3">{{ segment }} ms</div>
            </div>
            <div
                :style="{'background-image': 'linear-gradient(to right, whitesmoke 1px, transparent 1px)', 'background-size': `${grid.widthPercent}% 20%`}">
                <div v-for="row in series" class="my-2 inspector-timeline__segment">
                    <div class="inspector-timeline__segment-label"
                         :style="{'margin-left': row.marginPercent + '%'}">
                        {{ row.segment.label }} - {{ row.segment.duration }} ms
                    </div>
                    <div :style="{'margin-left': row.marginPercent + '%'}" class="inspector-timeline__segment-start-wrap">
                        <span class="inspector-timeline__segment-start">{{ row.segment.start }} ms</span>
                    </div>
                    <div class="inspector-timeline__segment-color min-w-1" :class="[row.color]"
                         :style="{width: row.widthPercent + '%', 'margin-left': row.marginPercent + '%'}"></div>
                </div>
            </div>
        </div>
        <div v-else class="inspector-timeline__empty">
            <div class="w-1/5">
                <HeartBeat class="inspector-timeline__empty-icon" />
            </div>
            <h3 class="inspector-timeline__empty-title">No data</h3>
        </div>
    </section>
</template>

<script>
import HeartBeat from "../../UI/Icons/HeartBeat";
export default {
    components: {HeartBeat},
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

<template>
    <div id="chart" v-if="series.length > 0" class="timeline fon">
        <apexchart type="rangeBar" :height="getHeight" :options="chartOptions" :series="series"></apexchart>
    </div>
</template>

<script>
import VueApexCharts from 'vue3-apexcharts'

export default {
    props: {
        event: Object
    },
    components: {
        apexchart: VueApexCharts,
    },
    data() {
        return {
            chartOptions: {
                chart: {
                    type: 'rangeBar',
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        enableShades: false,
                        distributed: true,
                        barHeight: '80%',
                        colors: {
                            backgroundBarRadius: 0,
                        },
                        dataLabels: {
                            hideOverflowingLabels: false,
                            position: 'bottom',
                        }
                    }
                },
                tooltip: {
                    custom: this.formatedTooltip
                },

                dataLabels: {
                    enabled: true,
                    textAnchor: 'start',
                    formatter: this.formatedLabels,
                    // offsetX: -40,
                    style: {
                        colors: ['#3c3c3c'],
                        fontWeight: 400,
                    }
                },
                xaxis: {
                    type: 'numeric',
                    decimalsInFloat: 2,
                    position: 'top',
                    tickAmount: 6,
                    labels: {
                        formatter: function (value, timestamp, opts) {
                            return `${value.toFixed(2)}ms`
                        },
                        style: {
                            colors: [],
                            fontSize: '12px',
                            fontFamily: 'Helvetica, Arial, sans-serif',
                            fontWeight: 700,
                            cssClass: 'apexcharts-xaxis-label',
                        },
                    }
                },
                yaxis: {
                    show: false,
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    }
                },
                grid: {
                    yaxis: {
                        lines: {
                            show: false,
                        }
                    },
                    xaxis: {
                        lines: {
                            show: true,
                        }
                    },
                    padding: {
                        top: 40,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },
                }
            },
        }
    },
    computed: {
        getHeight() {
            return this.series[0].data.length * 30 + 100;
        },
        series() {
            const seriesData = this.event.segments.map((i) => {
                return {
                    x: `${i.label}... (start: ${i.start} ms, duration: ${i.duration} ms)`,
                    y: [i.start, i.start + i.duration],
                    fillColor: this.event.setColorSegment(i.type)
                }
            });

            return [{data: seriesData}]
        }
    },
    methods: {
        formatedLabels(val, opts) {
            const label = opts.w.globals.labels[opts.dataPointIndex]
            return label;
        },
        formatedTooltip({series, seriesIndex, dataPointIndex, w}) {
            const label = w.globals.labels[dataPointIndex].split('... (')[0];
            const dur = w.globals.labels[dataPointIndex].split('duration:')[1];
            const duration = dur.substr(0, dur.length - 4)
            const persent = (+duration * 100 / this.event.process.duration).toFixed(2);
            return (
                '<div class="p-2 text-xs font-bold">' + '<span class="block text-gray-600 font-regular">' + label + ": " + "</span>" +
                duration + " ms " + " - " + persent + " % " + "</div>"
            );
        }
    },
}
</script>

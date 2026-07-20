import ApexCharts from "apexcharts";

export default function statisticsChart(statistics) {
    return {
        selected: "overview",
        chart: null,

        init() {
            this.$nextTick(() => {
                this.renderChart();
            });
        },

        renderChart() {
            const currentData = statistics[this.selected];

            const options = {
                series: currentData.series,

                legend: {
                    show: false,
                },

                colors: ["#097AEC", "#92BFFA"],

                chart: {
                    fontFamily: "Inter, sans-serif",
                    height: 310,
                    type: "area",
                    toolbar: {
                        show: false,
                    },
                },

                fill: {
                    gradient: {
                        enabled: true,
                        opacityFrom: 0.55,
                        opacityTo: 0,
                    },
                },

                stroke: {
                    curve: "straight",
                    width: 2,
                },

                markers: {
                    size: 0,
                },

                grid: {
                    xaxis: {
                        lines: {
                            show: false,
                        },
                    },
                    yaxis: {
                        lines: {
                            show: true,
                        },
                    },
                },

                dataLabels: {
                    enabled: false,
                },

                tooltip: {
                    y: {
                        formatter(value) {
                            return "IDR " + value.toLocaleString("id-ID");
                        },
                    },
                },

                xaxis: {
                    type: "category",
                    categories: currentData.labels,
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                    tooltip: {
                        enabled: false,
                    },
                },

                yaxis: {
                    labels: {
                        formatter(value) {
                            return value.toLocaleString("id-ID");
                        },
                    },
                    title: {
                        style: {
                            fontSize: "0px",
                        },
                    },
                },
            };

            this.chart = new ApexCharts(this.$refs.chart, options);
            this.chart.render();
        },

        changeChart(type) {
            this.selected = type;

            const currentData = statistics[type];

            this.chart.updateOptions({
                xaxis: {
                    categories: currentData.labels,
                },
            });

            this.chart.updateSeries(currentData.series);
        },
    };
}
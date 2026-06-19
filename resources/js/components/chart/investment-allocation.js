    export const allocationChart = () => {
    const chartElement = document.querySelector("#allocationChart");

    if (chartElement) {
        const raw = chartElement.dataset.chart;
        const data = raw ? JSON.parse(raw) : [];

        const labels = data.map((item) => item.label);
        const series = data.map((item) => item.value);

        const option = {
            chart: {
                fontFamily: "Inter, sans-serif",
                type: "donut",
                height: 400,
            },
            series: series,
            labels: labels,
            colors: [
                "#8A38F5",
                "#22C55E",
                "#EF4444",
                "#F59E0B",
                "#06B6D4",
                "#EC4899",
            ],
            plotOptions: {
                pie: {
                    expandOnClick: false,
                    donut: {
                        size: "80%",
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                // fontSize: "14px",
                                // fontWeight: "400",
                                // offsetY: -,
                                // color: "#fcfcfd",
                            },
                            value: {
                                show: true,
                                formatter: function (val) {
                                    const formatted = new Intl.NumberFormat(
                                        "id-ID",
                                    ).format(val);

                                    return `IDR ${formatted}`;
                                },
                                fontSize: "18px",
                                fontWeight: "600",
                                offsetY: 0,
                                color: "#1D2939",
                            },
                            total: {
                                show: true,
                                formatter: function (w) {
                                    const total = w.globals.seriesTotals.reduce(
                                        (a, b) => a + b,
                                        0,
                                    );

                                    const formatted = new Intl.NumberFormat(
                                        "id-ID",
                                    ).format(total);

                                    return `IDR ${formatted}`;
                                },
                                label: "Total",
                                // fontSize: "14px",
                                // fontWeight: "600",
                                // color: "#1D2939",
                            },
                        },
                    },
                },
            },

            legend: {
                show: true,
                // show: false,
                position: "bottom",
                // floating: false,
                
                // width: "100%",
                // height: 70,
                // offsetX: -35,
                // offsetY: 15,
                formatter: function (seriesName, opts) {
                    const value = opts.w.globals.series[opts.seriesIndex];
                    const formatted = new Intl.NumberFormat("id-ID").format(
                        value,
                    );

                    return `${seriesName} - IDR ${formatted}`;
                },
                onItemClick: {
                    toggleDataSeries: false,
                },
                markers: {
                    strokeWidth: 0,
                },
            },
            tooltip: {
                enabled: false,
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                width: 0,
            },
            states: {
                active: {
                    filter: {
                        type: "none",
                    },
                },
            },
        };

        const chart = new ApexCharts(chartElement, option);
        chart.render();
        return chart;
    }
    };

export const pieChart = () => {
    const chartElement = document.querySelector('#targetPieChart');

    if (chartElement) {
        const value = parseFloat(chartElement.dataset.value) || 0;

        const option = {
            chart: {
                fontFamily: "Inter, sans-serif",
                height: 200,
                type: 'radialBar',
                sparkline: {
                    enabled: true,
                }
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: '60%',
                    },
                    track: {
                        background: "#E4E7EC",
                    },
                    dataLabels: {
                        name: {
                            fontSize: "15px",
                            fontWeight: "400",
                            offsetY: 22,
                            color: "#fcfcfd",
                        },
                        value: {
                            fontSize: "30px",
                            fontWeight: "600",
                            offsetY: -18,
                            color: "#1D2939",
                        },
                    },
                }
            },
            series: [value],
            colors: ['#097AEC'],
            labels: ['Progress'],
            stroke: {
                lineCap: 'round'
            },
            fill: {
                type: 'solid',
                opacity: 1
            },
            states: {
                hover: {
                    filter: {
                        type: "none",
                    },
                },
                active: {
                    filter: {
                        type: "none",
                    },
                },
            },
        }

        const chart = new ApexCharts(chartElement, option);
        chart.render();
        return chart;
    }

}

export default pieChart;
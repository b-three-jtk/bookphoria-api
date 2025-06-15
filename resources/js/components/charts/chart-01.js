import ApexCharts from "apexcharts";

// ===== chartOne
const chart01 = () => {
  const chartSelector = document.querySelectorAll("#chartOne");

  if (chartSelector.length) {
    const chartElement = document.querySelector("#chartOne");
    const monthlyUsers = JSON.parse(chartElement.getAttribute('data-monthly-users') || '{}');
    const data = Object.values(monthlyUsers); // Ambil nilai dari monthly_users

    const chartOneOptions = {
      series: [
        {
          name: "Registrations",
          data: data,
        },
      ],
      colors: ["#e6604d"],
      chart: {
        fontFamily: "Outfit, sans-serif",
        type: "bar",
        height: 180,
        toolbar: {
          show: false,
        },
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: "39%",
          borderRadius: 5,
          borderRadiusApplication: "end",
        },
      },
      dataLabels: {
        enabled: false,
      },
      stroke: {
        show: true,
        width: 4,
        colors: ["transparent"],
      },
      xaxis: {
        categories: [
          "Jan",
          "Feb",
          "Mar",
          "Apr",
          "May",
          "Jun",
          "Jul",
          "Aug",
          "Sep",
          "Oct",
          "Nov",
          "Dec",
        ],
        axisBorder: {
          show: false,
        },
        axisTicks: {
          show: false,
        },
      },
      legend: {
        show: true,
        position: "top",
        horizontalAlign: "left",
        fontFamily: "Outfit",
        markers: {
          radius: 99,
        },
      },
      yaxis: {
        title: false,
      },
      grid: {
        yaxis: {
          lines: {
            show: true,
          },
        },
      },
      fill: {
        opacity: 1,
      },
      tooltip: {
        x: {
          show: false,
        },
        y: {
          formatter: function (val) {
            return val;
          },
        },
      },
    };

    const chartFour = new ApexCharts(chartElement, chartOneOptions);
    chartFour.render();
  }
};

export default chart01;
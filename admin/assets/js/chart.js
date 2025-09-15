async function loadChartData(chartId, color1, color2) {
    try {
      const response = await fetch('../api/admin/getMonthlyIncome.php');
      const data = await response.json();
      console.table(data)
  
      const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
      const paymentGatewayData = months.map((_, index) => data['Payment Gateway']?.[index] || 0);
      const bankTransferData = months.map((_, index) => data['Bank Transfer']?.[index] || 0);
  
      var options = {
        series: [{
            name: 'Payment Gateway',
            data: paymentGatewayData
          },
          {
            name: 'Bank Transfer',
            data: bankTransferData
          }
        ],
        legend: {
          show: false
        },
        chart: {
          type: 'area',
          width: '100%',
          height: 270,
          toolbar: {
            show: false
          },
          padding: {
            left: 0,
            right: 0,
            top: 0,
            bottom: 0
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth',
          width: 3,
          colors: [color2, color1]
        },
        grid: {
          borderColor: '#D1D5DB',
          strokeDashArray: 1,
          xaxis: {
            lines: {
              show: false
            }
          },
          yaxis: {
            lines: {
              show: true
            }
          },
          padding: {
            top: -20,
            right: 0,
            bottom: -10,
            left: 0
          }
        },
        fill: {
          type: 'gradient',
          gradient: {
            shade: 'light',
            type: 'vertical',
            shadeIntensity: 0.5,
            gradientToColors: [undefined, `${color2}00`],
            opacityFrom: [0.4, 0.4],
            opacityTo: [0.3, 0.3],
            stops: [0, 100]
          }
        },
        markers: {
          colors: [color2, color1],
          strokeWidth: 3,
          size: 0,
          hover: {
            size: 10
          }
        },
        xaxis: {
          labels: {
            show: true,
            style: {
              fontSize: '12px'
            }
          },
          categories: months
        },
        yaxis: {
          labels: {
            formatter: value => `LKR ${value}`,
            style: {
              fontSize: '9px'
            }
          }
        },
        tooltip: {
          x: {
            format: 'dd/MM/yy HH:mm'
          }
        }
      };
  
      var chart = new ApexCharts(document.querySelector(`#${chartId}`), options);
      chart.render();
    } catch (error) {
      console.error('Error loading chart data:', error);
    }
  }
  

window.onload = loadChartData('enrollmentChart', '#45B369', '#487fff');
  
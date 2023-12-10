@extends('AdminViews.Layout.layout')
@section('title','Users Report')
@section('style')
<style>

</style>

@endsection

@section('content')


  <main id="main" class="main">
    <section class="section dashboard">
      <div class="row bg-white shadow rounded-3">
        <div style="width: 80%; margin: 0 auto; text-align: center;" class="d-flex justify-content-center my-4">
            <!-- Interval selection and export buttons -->
            <select id="timeInterval" class="form-control w-50" onchange="updateChart()">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
            </select>
            <button class="btn btn-primary btn-sm mx-4" onclick="exportToExcel()">Export to Excel</button>
        </div>


        <div style="width: 80%; margin: 0 auto;">
            <canvas id="userReportChart"></canvas>
        </div>



      </div>
    </section>

  </main>
@endsection

@section('script')
<!-- Hammer.js for zoom functionality -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>
<!-- Chart.js zoom plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/1.3.0/chartjs-plugin-zoom.min.js"></script>
<!-- SheetJS for exporting to Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>

<script>
    function groupDataByInterval(data, interval) {
    const groupedData = {};

    data.forEach(item => {
        let key;
        const date = new Date(item.date);

        switch (interval) {
            case 'weekly':
                key = new Date(date.setDate(date.getDate() - date.getDay()));
                break;
            case 'monthly':
                key = new Date(date.getFullYear(), date.getMonth(), 1);
                break;
            case 'yearly':
                key = new Date(date.getFullYear(), 0, 1);
                break;
            default:
                key = new Date(date);
        }

        key = key.toISOString().split('T')[0];

        if (!groupedData[key]) {
            groupedData[key] = 0;
        }

        groupedData[key] += item.count;
    });

    return Object.entries(groupedData).map(([date, count]) => ({ date, count }));
}

function updateChart() {
    const interval = document.getElementById('timeInterval').value;
    const groupedData = groupDataByInterval(usersReportData, interval);

    userReportChart.data.labels = groupedData.map(data => data.date);
    userReportChart.data.datasets[0].data = groupedData.map(data => data.count);
    userReportChart.update();
}

window.onload = function() {
    // Prepare the data for Chart.js
    usersReportData = @json($usersReport);

    // Create the chart
    const ctx = document.getElementById('userReportChart').getContext('2d');
    userReportChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
            label: 'Users Joined',
            data: [],
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderWidth: 2,
            tension: 0.3
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            zoom: {
                pan: {
                    enabled: true,
                    mode: 'xy'
                },
                zoom: {
                    enabled: true,
                    mode: 'xy'
                }
            }
        }
    }
});

    updateChart();
}

// Export chart data to Excel
function exportToExcel() {
    const ws = XLSX.utils.json_to_sheet(userReportChart.data.datasets[0].data.map((value, index) => ({
        Date: userReportChart.data.labels[index],
        Users: value
    })));
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Users Joined");
    XLSX.writeFile(wb, "users_joined_report.xlsx");
}




   </script>
@endsection
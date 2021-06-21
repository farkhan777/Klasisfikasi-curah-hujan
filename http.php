<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js" integrity="sha512-O2Y8hD83PQtRf8vcr0N+yxwRtErIVaHJ4NOpojzq2yvUmhiJbQIT9OAYu27t+mVk814t+ongBVGx+YGylICVkQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js" integrity="sha512-GsqF810cNwHMCDELTwi3YgWIBwKYQlvC1WTAJ6fk80rtB6zN3IWdpoQujBQCuOMOxXXksEWwE0k4Lrb+N87DZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://github.com/nagix/chartjs-plugin-streaming/releases/download/v1.5.0/chartjs-plugin-streaming.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    </head>
    <body>
        <div>
            <div class="alert alert dark" id = "predicts" role = "alert">
            </div>
            <canvas id="myChart"></canvas>
        </div>

        <script type="text/javascript">
            var chartColors = {
                red: 'rgb(255, 99, 132)',
                orange: 'rgb(255, 159, 64)',
                yellow: 'rgb(255, 205, 86)',
                green: 'rgb(75, 192, 192)',
                blue: 'rgb(54, 162, 235)',
                purple: 'rgb(153, 102, 255)',
                grey: 'rgb(201, 203, 207)'
            };

            var label = ' ';
            var Tt = 0;
            var Hh = 0;
            var Pp = 0;

            function GetTt(){
                $.ajax({
                    url: 'getdata.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        key: 'getdataTt'
                    }, success: function (response) {
                        if(response != 'nodata'){
                            Tt = parseFloat(response);
                        }else{
                            Tt = 0;
                        }
                    }
                });
                return Tt;
            }

            function GetHh(){
                $.ajax({
                    url: 'getdata.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        key: 'getdataHh'
                    }, success: function (response) {
                        if(response != 'nodata'){
                            Hh = parseFloat(response);
                        }else{
                            Hh = 0;
                        }
                    }
                });
                return Hh;
            }

            function GetPp(){
                $.ajax({
                    url: 'getdata.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        key: 'getdataPp'
                    }, success: function (response) {
                        if(response != 'nodata'){
                            Pp = parseFloat(response);
                        }else{
                            Pp = 0;
                        }
                    }
                });
                return Hh;
            }

            function getLabel(){
                $.ajax({
                    url: 'getdata.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        key: 'getdatalabel'
                    }, success: function (response) {
                        if(response != 'nodata'){
                            label = '';
                            label = label + '<strong>' + Date(Date.now()).toString() + ' : </strong>' + response.toString();
                            $('#predicts').empty();
                            $('#predicts').append(label);
                        }else{
                            label = '';
                            label = label + '<strong>' + Date(Date.now()).toString() + ' : </strong> No Prediction Available';
                            $('#predicts').empty();
                            $('#predicts').append(label);
                        }
                    }
                });
            }

            function onRefresh(chart){
                chart.data.dataset[0].data.push({
                    x: Date.now(),
                    y: GetTt()
                });
                chart.data.dataset[1].data.push({
                    x: Date.now(),
                    y: GetHh()
                });
                chart.data.dataset[2].data.push({
                    x: Date.now(),
                    y: GetPp()
                });
                getLabel();
            }

            var color = Chart.helpers.color;
            var config = {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Dataset Temperature',
                        backgroundColor: color(chartColor.red).alpha(0.5).rgbString(),
                        borderColor: chartColors.red,
                        fill: false,
                        cubicInterpolationMode: 'monotone',
                        lineTension: 0;
                        borderDash: [8, 4],
                        data: []
                    }, {
                        label: 'Dataset Humidity',
                        backgroundColor: color(chartColor.blue).alpha(0.5).rgbString(),
                        borderColor: chartColors.red,
                        fill: false,
                        cubicInterpolationMode: 'monotone',
                        lineTension: 0;
                        borderDash: [8, 4],
                        data: []
                    }, {
                        label: 'Dataset Pressure',
                        backgroundColor: color(chartColor.green).alpha(0.5).rgbString(),
                        borderColor: chartColors.green,
                        fill: false,
                        cubicInterpolationMode: 'monotone',
                        lineTension: 0;
                        borderDash: [8, 4],
                        data: []
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Precipitation Data Streaming'
                    },
                    scales: {
                        xAxes: [{
                            type: 'realtime'
                        }],
                        yAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: 'value'
                            }
                        }]
                    },
                    tooltips: {
                        mode: 'nearest',
                        intersect: false
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: false
                    },
                    plugins: {
                        streaming: {
                            duration: 20000,
                            refresh: 1000,
                            delay: 2000,
                            onRefresh: onRefresh
                        }
                    }
                }
            };

            var ctx = document.getElementById('myChart').getContext('2d');
            window.myChart = new Chart(ctx, config);

            var colorNames = Object.keys(chartColors);
        </script>
        
    </body>
</html>
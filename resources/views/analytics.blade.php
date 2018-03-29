@extends('index')

@section('content')
    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        @if( session()->get('user')->is_teacher)
            <h1 class="display-4">Аналитика по студентам</h1>
            <p class="lead text-muted">В данном разделе представлена сводная информация по студентам, обучающимся
                вашим дисциплинами</p>
        @else
            <h1 class="display-4">Аналитика</h1>
            <p class="lead text-muted">В данном разделе представлена сводная информация по пройденным и доступным
                тестам</p>
        @endif
    </div>
    @if( session()->get('user')->is_teacher)
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">ФИО студента</th>
                <th scope="col">Группа</th>
                <th scope="col">Количество доступных тестов</th>
                <th scope="col">Количество пройденных тестов</th>
            </tr>
            </thead>
            <tbody>
            @foreach($students as $student)
                <tr>
                    <th scope="row">{{ $student->name }}</th>
                    <td>{{ $student->group->name }}</td>
                    <td>{{ $student->finished_tests->count()}}</td>
                    <td>{{ $student->available_tests->count() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Количество пройденных тестов</h5>
                        <p class="card-text">{{ $student->finishedTests->count() }} из {{$student->tests->count() }}
                            доступных</p>
                        <button id="testsBtn" class="btn btn-primary">Детали</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Среднее время прохождения тестов</h5>
                        <p class="card-text">{{ $student->avg_time }} минут</p>
                        <button id="timeBtn" class="btn btn-primary">Детали</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Процент правильных ответов</h5>
                        <p class="card-text">{{ $student->finishedTests->avg('right_answers_count') / ($student->finishedTests->avg('right_answers_count') + $student->finishedTests->avg('bad_answers_count')) * 100 }}
                            %</p>
                        <button id="answersBtn" class="btn btn-primary">Детали</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="chart_container">
            <canvas class="my-4" id="myChart" width="900" height="380"></canvas>
        </div>
    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <script type="text/javascript">
        var $ = jQuery;

        var DISCIPLINES = 0;
        var THEMES = 1;
        var TESTS_COUNTS = 0;
        var TIMES = 1;
        var PERCENT = 2;

        var currentTab = 0;
        var ctx = document.getElementById("myChart");
        var myChart;

        $(document).ready(function () {
            var isMobile = window.matchMedia("only screen and (max-width: 760px)");

            if (isMobile.matches) {
                ctx.setAttribute('width', "450");
                ctx.setAttribute('height', "500")
            }
        });
        $("#testsBtn").click(function () {
            $.ajax({
                type: 'get',
                url: 'analytics/tests',
                success: function (data) {
                    var labels = buildLabels(data, DISCIPLINES);
                    var dataset = buildTestsCountsDataSet(data, DISCIPLINES);
                    updateChart(labels, dataset, DISCIPLINES);
                    currentTab = TESTS_COUNTS;
                    buildButtons(data);
                }
            });
        });

        $("#timeBtn").click(function () {
            $.ajax({
                type: 'get',
                url: 'analytics/time',
                success: function (data) {
                    var labels = buildLabels(data, DISCIPLINES);
                    var dataset = buildTimeDataSet(data, DISCIPLINES);
                    updateChart(labels, dataset, DISCIPLINES);
                    currentTab = TIMES;
                    buildButtons(data);
                }
            });
        });

        $("#answersBtn").click(function () {
            $.ajax({
                type: 'get',
                url: 'analytics/answers',
                success: function (data) {
                    var labels = buildLabels(data, DISCIPLINES);
                    var dataset = buildPercentDataSet(data, DISCIPLINES);
                    updateChart(labels, dataset, DISCIPLINES);
                    currentTab = PERCENT;
                    buildButtons(data);
                }
            });
        });

        function updateChart(labels, dataset, type) {
            var label = type === 0 ? "по дисциплинам" : "по темам";
            if (myChart != null) {
                myChart.destroy();
                myChart = null;
            }
            buildChart(labels, dataset, label)

        }

        function buildChart(labels, dataset, label) {
            myChart = new Chart(ctx, {
                type: 'bar',
                options: {
                    responsive: true,
                },
                data: {
                    labels: labels,
                    datasets: [{
                        data: dataset,
                        label: label,
                        fill: false,
                        backgroundColor: ["rgba(255, 99, 132, 0.2)", "rgba(255, 159, 64, 0.2)", "rgba(255, 205, 86, 0.2)", "rgba(75, 192, 192, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(153, 102, 255, 0.2)", "rgba(201, 203, 207, 0.2)"],
                        borderColor: ["rgb(255, 99, 132)", "rgb(255, 159, 64)", "rgb(255, 205, 86)", "rgb(75, 192, 192)", "rgb(54, 162, 235)", "rgb(153, 102, 255)", "rgb(201, 203, 207)"],
                        borderWidth: 1
                    }]
                },
                options: {"scales": {"yAxes": [{"ticks": {"beginAtZero": true}}]}}
            });
        }

        function buildLabels(data, type) {
            switch (type) {
                case DISCIPLINES:
                    return data.disciplines.labels;
                case THEMES:
                    return data.themes.labels;
            }
        }

        function buildTestsCountsDataSet(data, type) {
            switch (type) {
                case DISCIPLINES:
                    return data.disciplines.counts;
                case THEMES:
                    return data.themes.counts;
            }
        }

        function buildTimeDataSet(data, type) {
            switch (type) {
                case DISCIPLINES:
                    return data.disciplines.times;
                case THEMES:
                    return data.themes.times;
            }
        }

        function buildPercentDataSet(data, type) {
            switch (type) {
                case DISCIPLINES:
                    return data.disciplines.percents;
                case THEMES:
                    return data.themes.percents;
            }
        }

        function buildButtons(data) {
            //todo добавить кнопки дисциплина тема чтобы изменять чарт)
        }

    </script>


@endsection
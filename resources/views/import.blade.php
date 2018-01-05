@extends('index')

@section('content')
    <section class="jumbotron ">
        <div class="container">

            <form class="form-horizontal" method="POST" action="{{ url('/import') }}" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group mb-2">
                    <label for="import_file">Выберите файл:</label>
                    <input id="import_file" name="import_file" type="file">
                </div>


                <div class="form-group">
                    <label for="selectDiscipline">Выберите дисциплину</label>
                    <select class="form-control" id="selectDiscipline" name="discipline_name" type="text">
                        @foreach($disciplines as $discipline)
                            <option>{{ $discipline->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="themes" style="visibility: hidden">

                    <div class="form-group">
                        <label for="selectTheme">Выберите тему</label>
                        <select class="form-control" id="selectTheme" name="theme_name" type="text">
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="testName">Введите название теста:</label>
                        <input type="text" class="form-control" id="testName" name="testName" required>
                    </div>

                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Импортировать</button>
                </div>

                @include('layouts.errors')
            </form>
        </div>
    </section>

    <script type="text/javascript">
        var $ = jQuery;
        $("#selectDiscipline").change(function () {
            var str = "";
            $("#selectDiscipline option:selected").each(function () {
                str += $(this).text() + " ";
            });
            $.ajax({
                type: 'get',
                url: '/themesByName',
                data: {'disciplineName': str},
                success: function (data) {
                    console.log(data);
                    $('.themes').css('visibility', 'visible');
                    $('#selectTheme').html(data);
                }
            });
        });

    </script>

@endsection
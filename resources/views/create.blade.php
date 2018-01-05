@extends('index')

@section('content')
    <section class="jumbotron ">
        <div class="container">

            @if($type == 'discipline')
                <h1 class="text-center">Создание новой дисциплины</h1>

                <form method="POST" action="/createDiscipline">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="selectGroup">Выберите группу</label>
                        <select class="form-control" id="selectGroup" name="group_name" type="text">
                            @foreach($groups as $group)
                                <option>{{ $group }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="name">Название дисциплины</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

            @elseif($type == 'theme')
                <h1 class="text-center">Создание новой темы</h1>

                <form method="POST" action="/createTheme">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="selectDiscipline">Выберите дисциплину</label>
                        <select class="form-control" id="selectDiscipline" name="discipline_name" type="text">
                            @foreach($disciplines as $discipline)
                                <option>{{ $discipline->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="name">Название темы</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
            @endif

                     <div class="form-group">
                         <button type="submit" class="btn btn-primary">Создать</button>
                     </div>

                     @include('layouts.errors')
                </form>
        </div>
    </section>
@endsection
@extends('index')

@section('content')
    <section class="jumbotron text-center">
        <div class="container">
            <h1 class="jumbotron-heading">Добро пожаловать на сайт<br> Ready to Study</h1>
            <p class="lead text-muted">Здесь вы можете бла бла бла, а если вы преподаватель, то вы можете создать новые
                дисциплины, темы, тесты</p>
            @if(session()->get('user') != null)
                @if(session()->get('user')->is_teacher)
                    <p>
                        <a href="{{ url('createDiscipline') }}" class="btn btn-primary">Создать новую дисциплину</a>
                    </p>
                    <p>
                        <a href="{{ url('createTheme') }}" class="btn btn-primary">Создать новую тему</a>
                    </p>
                    <p>
                        <a href="{{ url('import') }}" class="btn btn-primary">Добавить новый тест</a>
                    </p>
                @endif
            @endif
        </div>
    </section>
@endsection
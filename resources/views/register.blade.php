@extends('index')

@section('content')
    <section class="jumbotron ">
        <div class="container">
            <h1 class="text-center">Регистрация</h1>

            <form method="POST" action="/register">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="selectGroup">Выберите группу</label>
                    <select class="form-control" id="selectGroup" name="group_name" type="text">
                        @foreach($groups as $group)
                            <option>{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label name="name">Имя (ФИО):</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Иванов Иван Иванович"
                           required>
                </div>
                <div class="form-group">
                    <label name="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="email" required>
                    <small id="emailHelp" class="form-text text-muted">Убедитесь в правильности введенного вами email.
                    </small>
                </div>
                <div class="form-group">
                    <label name="password">Пароль:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <small id="passwordHelp" class="form-text text-muted">Убедитесь в правильности введенного вами
                        пароля.
                    </small>
                </div>
                <div class="form-group">
                    <label name="password_confirmation">Повторите пароль:</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                           required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Sign up</button>
                </div>
                @include('layouts.errors')
            </form>

        </div>
    </section>
@endsection
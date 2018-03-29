@extends('index')

@section('content')
    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-4">Профиль</h1>
        <p class="lead text-muted">Здесь вы можете просмотреть, а также изменить ваши данные</p>
    </div>
    <form method="POST" action="{{ url("/user/update") }}">
        <div class="mb-3">
            <label for="username">Имя (ФИО):</label>
            <div class="input-group">
                <input type="text" class="form-control" id="name" value="{{ session()->get('user')->name }}" required="">
            </div>
        </div>

        <div class="mb-3">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" value="{{ session()->get('user')->email }}" required="">
        </div>

        <div class="mb-3">
            <label for="state">Группа:</label>
            <select class="custom-select d-block w-100" id="group" required="">
                <option value="">{{ $group->name }}</option>
                @foreach($groups as $gr)
                    <option>{{ $gr->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary btn-lg btn-block" type="submit">Сохранить</button>
    </form>

@endsection


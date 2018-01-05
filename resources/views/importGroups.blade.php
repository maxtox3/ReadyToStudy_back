@extends('index')

@section('content')
    <section class="jumbotron ">
        <div class="container">
            <h1>Импорт списка групп</h1>

            <form class="form-horizontal" method="POST" action="{{ url('/importGroups') }}" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group mb-2">
                    <label for="import_file">Выберите файл:</label>
                    <input id="import_file" name="import_file" type="file">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Импортировать</button>
                </div>

                @include('layouts.errors')
            </form>
        </div>
    </section>
@endsection
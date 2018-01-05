@extends('index')

@section('content')
    <section class="jumbotron ">
        <div class="container">
            <h1 class="text-center">Авторизация</h1>

            <form method="POST" action="/login">
                {{ csrf_field() }}

                <div class="form-group">
                    <label name="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="email" required>

                </div>
                <div class="form-group">
                    <label name="password">Пароль:</label>
                    <input type="password" class="form-control" id="password" name="password" required>

                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Войти</button>
                </div>

                @include('layouts.errors')
            </form>

        </div>
    </section>
@endsection

{{--<section class="jumbotron text-center">--}}
{{--<div class="container">--}}
{{--<h1 class="jumbotron-heading">Album example</h1>--}}
{{--<p class="lead text-muted">Something short and leading about the collection below—its contents, the creator, etc. Make it short and sweet, but not too short so folks don't simply skip over it entirely.</p>--}}
{{--<p>--}}
{{--<a href="#" class="btn btn-primary">Main call to action</a>--}}
{{--<a href="#" class="btn btn-secondary">Secondary action</a>--}}
{{--</p>--}}
{{--</div>--}}
{{--</section>--}}

{{--<div class="album text-muted">--}}
{{--<div class="container">--}}

{{--<div class="row">--}}
{{--<div class="card">--}}
{{--<img data-src="holder.js/100px280?theme=thumb" alt="Card image cap">--}}
{{--<p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>--}}
{{--</div>--}}
{{--<div class="card">--}}
{{--<img data-src="holder.js/100px280?theme=thumb" alt="Card image cap">--}}
{{--<p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>--}}
{{--</div>--}}
{{--<div class="card">--}}
{{--<img data-src="holder.js/100px280?theme=thumb" alt="Card image cap">--}}
{{--<p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>--}}
{{--</div>--}}

{{--<div class="card">--}}
{{--<img data-src="holder.js/100px280?theme=thumb" alt="Card image cap">--}}
{{--<p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>--}}
{{--</div>--}}
{{--<div class="card">--}}
{{--<img data-src="holder.js/100px280?theme=thumb" alt="Card image cap">--}}
{{--<p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>--}}
{{--</div>--}}
{{--<div class="card">--}}
{{--<img data-src="holder.js/100px280?theme=thumb" alt="Card image cap">--}}
{{--<p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>--}}
{{--</div>--}}

{{--<div class="card">--}}
{{--<img data-src="holder.js/100px280?theme=thumb" alt="Card image cap">--}}
{{--<p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>--}}
{{--</div>--}}
{{--<div class="card">--}}
{{--<img data-src="holder.js/100px280?theme=thumb" alt="Card image cap">--}}
{{--<p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>--}}
{{--</div>--}}
{{--<div class="card">--}}
{{--<img data-src="holder.js/100px280?theme=thumb" alt="Card image cap">--}}
{{--<p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>--}}
{{--</div>--}}
{{--</div>--}}

{{--</div>--}}
{{--</div>--}}
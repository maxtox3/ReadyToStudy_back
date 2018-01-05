<header>
    <div class="collapse bg-dark" id="navbarHeader">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 py-4">
                    <h4 class="text-white">About</h4>
                    <p class="text-muted">Add some information about the album below, the author, or any other
                        background context. Make it a few sentences long so folks can pick up some informative tidbits.
                        Then, link them off to some social networking sites or contact information.</p>
                </div>
                <div class="col-sm-4 py-4">
                    <h4 class="text-white">Contact</h4>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">Follow on Twitter</a></li>
                        <li><a href="#" class="text-white">Like on Facebook</a></li>
                        <li><a href="#" class="text-white">Email me</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="navbar navbar-dark bg-dark">
        <div class="container d-flex justify-content-between">
            <a href="#" class="navbar-brand">Ready to Study</a>
            <nav>
                <button class="navbar-toggler float-right" type="button" data-toggle="collapse"
                        data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false"
                        aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <ul class="nav nav-pills float-right">

                    @if(session()->get('user') != null)
                        <li>
                            <a class="nav-link ml-auto" href="#">{{ session()->get('user')->name }}</a>
                        </li>

                        @if( session()->get('user')->is_teacher)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="" id="dropdown07" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Импорт</a>
                                <div class="dropdown-menu" >
                                    <a class="dropdown-item" href="{{ url('importGroups') }}">Список групп</a>
                                    <a class="dropdown-item" href="{{ url('import') }}">Новый Тест</a>
                                </div>
                            </li>
                        @endif
                        <li>
                            <a class="nav-link" href="{{ url('logout') }}">Выйти </a>
                        </li>
                    @endif

                    @if(session()->get('user') == null)
                        <li>
                            <a class="nav-link" href="{{ url('login') }}">Войти</a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('register') }}">Зарегистрироваться </a>
                        </li>
                    @endif

                </ul>
            </nav>
        </div>
    </div>
</header>
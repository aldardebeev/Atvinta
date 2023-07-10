<nav class="navbar bg-light border-bottom">
    <div class="container justify-content-center">
        <div class="col-lg-6">
            <a class="ml-0 navbar-brand text-primary" href="{{ route('home') }}">
                Pastebin
            </a>
            <span class="navbar-text d-none d-md-inline">Тестовое задание Атвинта.</span>

        </div>
        <div class="col-1 d-none d-md-block">
            <ul class="nav float-end navbar-nav">
                <li class="nav-item dropdown text-end">
                    <a href="{{ route('page.note.new') }}" class="btn btn-outline-success" type="submit">Новая паста</a>
                </li>
                <li class="nav-item dropdown text-end">
                    @auth
                        <a href="{{ route('myNotes') }}" class="btn btn-outline-success" type="submit">Мои пасты</a>
                    @endauth
                </li>
            </ul>
        </div>
        <div class="col-1">
            <ul class="nav float-start navbar-nav">
                    @auth
                        <span class="navbar-text">
                            {{ Auth::user()->name }}
                        </span>
                    @endauth
            </ul>
        </div>
        <div class="col-1">
            @auth
                <a href="{{ route('user.logout') }}" class="btn btn-outline-success" type="submit">Выйти</a>
            @endauth
            @guest
                <a href="{{ route('user.signup') }}" class="btn btn-outline-success" type="submit">Войти</a>
            @endguest
        </div>
    </div>
</nav>

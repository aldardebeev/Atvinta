@php
    use Illuminate\Support\Facades\App;
@endphp

<nav class="navbar bg-light border-bottom">
    <div class="container justify-content-center">
        <div class="col-lg-6">
            <a class="ml-0 navbar-brand text-primary" href="{{ route('home') }}">
                Pastebin
            </a>
            <span class="navbar-text d-none d-md-inline">Тестовое задание Атвинта.</span>
        </div>
        <div class="col-1 d-none d-md-block" style="margin-right: 10px;">
            <ul class="nav float-end">
                <li class="nav-item dropdown text-end">
                    <a href="{{ route('user.myNotes') }}" class="btn btn-outline-success" type="submit">Мои заметки</a>
                </li>
            </ul>
        </div>

        <div class="col-1 d-none d-md-block" style="margin-right: 10px;">
            <ul class="nav float-end">
                <li class="nav-item dropdown text-end">
                    <a href="{{ route('page.note.new') }}" class="btn btn-outline-success" type="submit">Новая заметка</a>
                </li>
            </ul>
        </div>
        <div class="col-1 d-none d-md-block" style="margin-right: 10px;">
            <ul class="nav float-end">
                <li class="nav-item dropdown text-end">
                    <a href="{{ route('user.signup') }}" class="btn btn-outline-success" type="submit">Войти</a>
                </li>
            </ul>
        </div>
        <div class="col-1 d-none d-md-block" style="margin-right: 10px;">
            <ul class="nav float-end">
                <li class="nav-item dropdown text-end">
                    <a href="{{ route('user.logout') }}" class="btn btn-outline-success" type="submit">Выйти</a>

                </li>
            </ul>
        </div>


    </div>
</nav>

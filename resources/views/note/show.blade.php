@extends('layout.app')

@section('title', 'Secret note read and destroyed!')

@section('content')
    @if (session('success'))
        <div class="container">
            <div class="row mt-2 mb-4">
                <div class="col-md-12 mx-auto">
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="container">
        <div class="row mt-2 mb-4">
            <div class="col-md-12 mx-auto">
                <h3 class="mt-5">Содержание пасты - {{ $note_title }}</h3>

                @if ($text_type === 'text')
                    <div class="card-text" style="word-wrap: break-word;">
                        {{ $note_text }}
                    </div>
                @elseif ($text_type === 'php')
                    <pre><code class="language-php">{{ $note_text }}</code></pre>
                @elseif ($text_type === 'html')
                    <pre><code class="language-html">{{ $note_text }}</code></pre>
                @endif

            </div>
        </div>
    </div>

{{--    @if (Auth::check())--}}
        <!-- Отображение формы комментария -->

        <div class="container">

            <div class="row">
                <div class="col-md-6 mx-auto">
                    <h4>Оставить жалобу</h4>
                    <form action="/note/comment/{{ $id }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <textarea class="form-control" name="text" rows="3" placeholder="Введите ваш комментарий"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </form>
                </div>
            </div>

            @if (!Auth::check())
                <div class="container">
                    <div class="row mt-2 mb-4">
                        <div class="col-md-12 mx-auto">
                            <div class="alert alert-danger" role="alert">
                                Нужно <a href="/signin">войти в профиль</a>, чтобы оставить комментарий.
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

@endsection

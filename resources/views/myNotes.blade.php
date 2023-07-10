@extends('layout.app')

@section('title', 'Create secret notes that will self-destruct after being read')

@section('content')
    <div class="container">
        <h4 class="mt-2">Пасты пользователя </h4>
        <div class="row">
            <div class="col-md-10 offset-md-1">
                @foreach ($notesData as $note)
                    <div class="card mb-3">
                        <div class="card-body">
                            <a href="http://localhost:8000/note/{{ $note['slug'] }}">
                                <h5 class="card-title">{{ $note['title'] }}</h5>
                            </a>
                            @if ($note['text_type'] === 'text')
                                <div class="card-text" style="word-wrap: break-word;">
                                    {{ $note['noteText'] }}...
                                </div>
                            @elseif ($note['text_type'] === 'php')
                                <pre><code class="language-php">{{ $note['noteText'] }}...</code></pre>
                            @elseif ($note['text_type'] === 'html')
                                <pre><code class="language-html">{{ $note['noteText'] }}...</code></pre>
                            @endif

                            <p class="card-text text-end">{{ $note['access_type'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection

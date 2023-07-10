@extends('layout.app')

@section('title', 'Create secret notes that will self-destruct after being read')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                @foreach ($notes as $note)
                    <div class="card mb-3">
                        <div class="card-body">
                            <a href="http://localhost:8000/note/{{ $note['slug'] }}">
                                <h5 class="card-title">{{ $note['title'] }}</h5>
                            </a>
                            @if ($note['text_type'] === 'text')
                                <div class="card-text" style="word-wrap: break-word;">
                                    {{ $note['text'] }} ...
                                </div>
                            @elseif ($note['text_type'] === 'php')
                                <pre><code class="language-php">{{ $note['text'] }} ... </code></pre>
                            @elseif ($note['text_type'] === 'html')
                                <pre><code class="language-html">{{ $note['text'] }} ... </code></pre>
                            @endif
                            @if (isset($note['user_name']))
                                <p class="card-text  text-success">Автор: {{ $note['user_name'] }}</p>
                            @else
                                <p  class="card-text text-muted" style="margin-right: 10px;">Автор: Не зарегистрированный</p>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/note/create.js')}}"></script>

    {{-- Disable submit button if note is empty --}}
    <script>
        $(document).ready(function(){
            $('#create_note_form__submit_btn').prop('disabled',true);
            $('#text').keyup(function(){
                $('#create_note_form__submit_btn').prop('disabled', this.value == "" ? true : false);
            })
        });
    </script>
@endpush

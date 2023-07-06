@extends('layout.app')

@section('title', 'Create secret notes that will self-destruct after being read')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                @foreach ($notes as $note)

                        <div class="card mb-3">
                            <div class="card-body">
                                <a href="http://localhost:8000/note/{{ $note['slug'] }}">
                                    <h5 class="card-title">{{ $note['title'] }}</h5>
                                </a>
                                <p class="card-text">{{ $note['text'] }}</p>
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

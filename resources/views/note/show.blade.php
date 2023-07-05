@extends('layout.app')

@section('title', 'Secret note read and destroyed!')

@section('content')

    <div class="container">
        <div class="row mt-2 mb-4">
            <div class="col-md-8 mx-auto">
                <h3 class="mt-5">Содержание заметки</h3>


                <h3 class="mt-5">{{ $note_title }}</h3>
                <div class="mb-3">
                    <textarea class="form-control" id="note-text" rows="8">{{ $note_text }}</textarea>
                </div>

                <div class="mb-3">
                    <div class="d-grid">
                        <button id="copy-button" type="button" class="btn btn-outline-secondary">
                            Скопировать заметку
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#copy-button').click(function (e) {
                e.preventDefault();

                var note_text = document.getElementById("note-text");
                var copy_btn = document.getElementById("copy-button");


                note_text.select();
                note_text.setSelectionRange(0, 99999);

                navigator.clipboard.writeText(note_text.value);

                note_text.classList.add("is-valid");
                copy_btn.innerText = 'Copied!';
            });
        });
    </script>
@endpush

@extends('layout.app')

@section('title', 'Create secret notes that will self-destruct after being read')

@section('content')
    <div class="container">
        <h4 class="mt-2">Заметки пользователя <strong>{{ $name }}:</strong></h4>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                @foreach ($notes as $index => $note)
                    <div class="card mb-3">
                        <div class="card-body">
                            <a href="http://localhost:8000/note/{{ $slug[$index] }}">
                                <h5 class="card-title">{{ isset($title[$index]) ? $title[$index] : '' }}</h5>
                            </a>
                            <p class="card-text">{{ $notes[$index] }}</p>
                            <p class="card-text text-end">{{ isset($access_type[$index]) ? $access_type[$index] : '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

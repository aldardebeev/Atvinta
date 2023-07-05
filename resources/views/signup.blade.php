@extends('layout.app')

@section('title', 'Create secret notes that will self-destruct after being read')

@section('content')
    <div class="container">
        <h1>Sign up</h1>
        <form action="" method="POST">
            @csrf

            <label>
                <input type="text" name="name" placeholder="Name" required />
            </label>

            <label>
                <input type="password" name="password" placeholder="Password" required />
            </label>

            <label>
                <input type="email" name="email" placeholder="Email" required />
            </label>

            <button type="submit" >Sign Up</button>
        </form>
        <div class="text-center">Already have an account? <a href="/signin">Login here</a></div>
    </div>
@endsection


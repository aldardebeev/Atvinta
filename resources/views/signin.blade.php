@extends('layout.app')

@section('title', 'Create secret notes that will self-destruct after being read')

@section('content')


    <body>
    <div class="container">
        <h1>Sign in</h1>
        <form action="" method="POST">
            @csrf

            <label>
                <input type="email" name="email" placeholder="Email" required />
            </label>

            <label>
                <input type="password" name="password" placeholder="Password" required />
            </label>


            <button type="submit">Sign In</button>

        </form>
    </div>
    </body>



@endsection







<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <div class="container-fluid">
        <span class="navbar-text">Assignment</span>

        <ul class="navbar-nav"> 
            @auth
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('homepage') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link"  style="text-decoration: none; cursor: pointer;">Logout</button>
                    </form>
                </li>
            @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('loginpage') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('registerpage') }}">Register</a>
                    </li>
            @endauth
        </ul>
    </div>
</nav>

<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
  <button id="hamburguesa"><i class="fas fa-bars"></i></button>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <i class="fas fa-ellipsis-v"></i>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
      <li class="nav-item active">
        <a class="nav-link" href="#"><i class="fas fa-bell"></i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><i class="fas fa-envelope"></i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{route('logout')}}"><i class="fas fa-sign-out-alt"></i></i>Salir</a>
      </li>
    </ul>
  </div>
</nav>

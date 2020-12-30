<!DOCTYPE html>
<html style="scroll-behavior: smooth;" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="eJornal sistema de gestion medica" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('img/logos/favicon.png')}}">
    <title>@yield('title', 'eJornal')</title>

    <link rel="stylesheet" href="/css/web_oficial.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.18.0/css/mdb.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('font_awesome/fontawesome-all.min.css')}}">

    {{-- Fuente --}}
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
</head>

<body class="fondo_login">

    <div class="login_contenedor_gral">

        <div class="container-fluid mt-3 mb-5">
            <section>
                <div class="mask d-flex justify-content-center align-items-center">
                    <div class="container py-5 my-5">
                        <h3 class="font-weight-bold text-center grey-text pb-2">eJornal</h3>
                        <p class="lead text-center grey-text pt-2 mb-5">Iniciar sesión</p>
                        <div class="row d-flex align-items-center justify-content-center">
                            <div class="col-md-6 col-xl-5">
                                @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{$error}}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endforeach
                                @endif
                                @include('mensajes_validacion')
                                <div class="card">

                                    <div class="card-body z-depth-2 px-4">
                                        <form action="{{action('UserController@login')}}" method="post">
                                          {{ csrf_field() }}
                                        <div class="md-form">
                                            <i class="fa fa-envelope prefix grey-text"></i>
                                            <input type="email" name="email" id="form2" class="form-control">
                                            <label for="form2">Email</label>
                                        </div>
                                        <div class="md-form">
                                            <i class="fas fa-key prefix grey-text"></i>
                                            <input type="password" id="form4" name="password" class="form-control">
                                            <label for="form4">Contraseña</label>
                                        </div>
                                        <div class="text-center my-3">
                                            <button class="btn btn-info btn-block">Ingresar</button>
                                        </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </div>




    <script src="{{ mix('js/app.js') }}" defer></script>
    {{-- Este es el JS de MDB (Material Design Bootstrap). Funciona solo si se carga desde aquí, luego compilar todo el Mix de JS --}}
    <script defer type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.18.0/js/mdb.min.js"></script>

</body>

</html>

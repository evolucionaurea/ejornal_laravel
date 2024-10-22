<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" sizes="32x32" href="{{asset('img/logos/isologo.png')}}">

	<title>@yield('title', 'Ejornal')</title>


	{{--
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
		integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	--}}
	<link rel="stylesheet" href="{{asset('css/lib/bootstrap.min.css')}}">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="{{asset('font_awesome/fontawesome-all.min.css')}}">

	{{--
	<link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet"> --}}
	<link rel="stylesheet" href="{{asset('css/lib/materialdesignicons.min.css')}}">

	<!-- Material Design Bootstrap -->
	{{--
	<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.0/css/mdb.min.css" rel="stylesheet"> --}}
	<link rel="stylesheet" href="{{asset('css/lib/mdb.min.css')}}">

	<!-- Data Table -->
	{{--
	<link rel="stylesheet" type="text/css"
		href="https://cdn.datatables.net/v/bs4-4.1.1/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-flash-1.6.2/b-html5-1.6.2/r-2.2.5/sc-2.0.2/datatables.min.css" />
	--}}
	<link rel="stylesheet" href="{{asset('css/lib/datatables.min.css')}}">

	<!-- Data Table para que funcione Admin - Reportes -->
	{{--
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> --}}
	<link rel="stylesheet" href="{{asset('css/lib/jquery-ui.css')}}">


	<!-- Css de Vuetify -->
	<link rel="stylesheet" href="{{asset('vuetify/vuetify.min.css')}}">


	<link rel="stylesheet" href="{{ mix('css/app.css') }}">

	{{-- Select 2 --}}
	{{--
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
	<link rel="stylesheet" href="{{asset('css/lib/select2.min.css')}}">



	{{-- <script defer type="text/javascript" src="{{asset('js/lib/chart.min.js')}}"></script> --}}
	{{-- Graficos con Chart JS --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.2/chart.min.js" type="text/javascript"></script>
	{{-- Graficos con Chart JS --}}
	<script type="text/javascript" src="{{ asset('js/lib/jquery-3.3.1.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/lib/jquery-ui.js') }}"></script>
	{{-- <script type="text/javascript" src="{{asset('js/lib/jquery.dataTables.min.js')}}"></script> --}}
	<script type="text/javascript" src="{{asset('js/lib/mdb.min.js')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/lib/select2.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/lib/pdfmake.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/lib/vfs_fonts.js')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/lib/datatables.min.js')}}"></script>


</head>

<body>


	<div id="app">
		@yield('content')
	</div>


	{{-- <script type="application/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script> --}}

	{{-- <script type="application/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}

	{{-- <script type="application/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js">
	</script> --}}
	<!-- Data Table para que funcione Admin - Reportes -->




	{{-- Este es el JS de MDB (Material Design Bootstrap). Funciona solo si se carga desde aquí, luego compilar todo el
	Mix de JS --}}
	{{-- <script defer type="text/javascript"
		src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.18.0/js/mdb.min.js"></script> --}}
	{{-- Este es el JS de MDB (Material Design Bootstrap). Funciona solo si se carga desde aquí, luego compilar todo el
	Mix de JS --}}


	{{-- Data Tables --}}
	{{-- <script defer type="text/javascript"
		src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
	<script defer type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js">
	</script>
	<script defer type="text/javascript"
		src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-flash-1.6.2/b-html5-1.6.2/b-print-1.6.2/r-2.2.5/sc-2.0.2/datatables.min.js">
	</script> --}}
	{{-- Data Tables --}}


	{{-- Select 2 --}}
	{{-- <script defer src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
	{{-- Select 2 --}}



	<footer class="footer">
		<div class="container">
			Jornal Salud. Copyright &copy; {{ date('Y') }} - Todos los derechos reservados.
		</div>
	</footer>





	<script>
		var route = '{{ Route::currentRouteName() }}';
		var csfr = '{{ csrf_token() }}';
	</script>

	<script src="{{ mix('js/app.js') }}"></script>



	<!-- POPUPS -->
	<div id="popups" class="modal fade" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body"></div>

			</div>
		</div>
	</div>


	<!-- LOADING -->
	<div id="loading">
		<div class="content">
			<div class="icon"><i class="fa fa-cog fa-spin"></i></div>
			<p class="text">trabajando....</p>
		</div>
	</div>



</body>

</html>
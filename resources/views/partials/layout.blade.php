<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" sizes="32x32" href="{{asset('img/logos/isologo.png')}}">

	<title>@yield('title', 'Ejornal')</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">


	<link rel="stylesheet" href="{{asset('css/lib/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('font_awesome/fontawesome-all.min.css')}}">	
	<link rel="stylesheet" href="{{asset('css/lib/materialdesignicons.min.css')}}">	
	<link rel="stylesheet" href="{{asset('css/lib/mdb.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/lib/jquery-ui.css')}}">
	<link rel="stylesheet" href="{{asset('vuetify/vuetify.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/lib/select2.min.css')}}">
	
	{{-- eliminar --}}
	<link rel="stylesheet" href="{{asset('css/lib/toastr.min.css')}}">
	{{-- <link rel="stylesheet" href="{{asset('css/lib/datatables.min.css')}}"> --}}


	<link rel="stylesheet" href="{{mix('css/app.css')}}">	
	
	
	{{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.2/chart.min.js" ></script> --}}
	
	<script type="text/javascript" src="{{asset('js/lib/jquery-3.3.1.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/lib/jquery-ui.js')}}"></script>	
	{{-- <script type="text/javascript" src="{{asset('js/lib/mdb.min.js')}}"></script> --}}
	<script type="text/javascript" src="{{asset('js/lib/popper.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/lib/bootstrap.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/lib/select2.min.js')}}"></script>

	
	{{-- eliminar --}}	
	{{-- <script type="text/javascript" src="{{asset('js/lib/pdfmake.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/lib/vfs_fonts.js')}}"></script> --}}
	{{-- <script defer type="text/javascript" src="{{asset('js/lib/datatables.min.js')}}"></script> --}}
	{{-- <script defer type="text/javascript" src="{{asset('js/lib/toastr.min.js')}}"></script> --}}


</head>

<body>


	<div id="app">
		@yield('content')
	</div>

	<footer class="footer">
		<div class="container">
			Jornal Salud. Copyright &copy; {{ date('Y') }} - Todos los derechos reservados.
		</div>
	</footer>

	<script>
		var route = '{{ Route::currentRouteName() }}';
		var csfr = '{{ csrf_token() }}';
	</script>

	<script src="{{ mix('js/app.optimized.js') }}"></script>


	@php
		$routeName = Route::currentRouteName();
		$routeName = preg_replace('/^\//', '', $routeName);
		$routeJsBase = 'js/' . str_replace('.', '/', $routeName);
		//echo $routeJsBase;
	@endphp
	@if (file_exists(public_path($routeJsBase . '.js')))
	<script defer src="{{ mix($routeJsBase . '.js') }}"></script>
	@endif



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
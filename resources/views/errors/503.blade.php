@extends('partials.layout')

@section('title', 'Error503')

@section('content')

<section class="container my-5 py-5">

	<div class="row">
		<div class="col-lg-4 offset-4">

		  <div class="text-center px-md-5 mx-md-5 dark-grey-text z-depth-1 py-5">
		    <div class="mb-4 error_404">
		      <i class="fas fa-info-circle"></i>
		    </div>
		    <h3 class="font-weight-bold mb-4 pb-2">Sitio en Mantenimiento</h3>
		    <p>En estos momentos estamos actualizando el sitio.<br />Refresca esta pantalla en unos minutos y podrás volver a usar el sistema.<br><br>Muchas Gracias.</p>


		  </div>
		</div>
	</div>
</section>

@endsection

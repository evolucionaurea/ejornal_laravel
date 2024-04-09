<?php

use Illuminate\Database\Seeder;
use App\AusentismoDocumentacion;
use App\AusentismoDocumentacionArchivos;


class AusentismoDocumentacionArchivosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $ausentismos_documentacion = AusentismoDocumentacion::select('id','archivo','hash_archivo')->take(10)->get();

      $aus_doc_archivos = AusentismoDocumentacionArchivos::all();

      ///dd($aus_doc_archivos);

      foreach($ausentismos_documentacion as $doc){

      	if(!$doc->archivo) continue;
      	$au_doc_archivo = new AusentismoDocumentacionArchivos;

      	if($aus_doc_archivos){
      		foreach($aus_doc_archivos as $s_archivo){
      			if($s_archivo->ausentismo_documentacion_id==$doc->id && $s_archivo->hash_archivo==$doc->hash_archivo){
      				$au_doc_archivo->id = $s_archivo->id;
      			}
      		}
      	}

      	$au_doc_archivo->ausentismo_documentacion_id = $doc->id;
      	$au_doc_archivo->archivo = $doc->archivo;
      	$au_doc_archivo->hash_archivo = $doc->hash_archivo;
      	$au_doc_archivo->save();

      }
    }
}

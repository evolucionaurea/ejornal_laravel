<?php

use Illuminate\Database\Seeder;
use App\Preocupacional;
use App\PreocupacionalArchivo;


class PreocupacionalesArchivosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $archivos = Preocupacional::select('id','archivo','hash_archivo')->get();

      $saved_archivos = PreocupacionalArchivo::all();

      ///dd($aus_doc_archivos);

      foreach($archivos as $doc){

      	if(!$doc->archivo) continue;
        $preocup_archivo = new PreocupacionalArchivo;

      	if($saved_archivos){
      		foreach($saved_archivos as $s_archivo){
      			if($s_archivo->preocupacional_id ==$doc->id && $s_archivo->hash_archivo==$doc->hash_archivo){
              $preocup_archivo = PreocupacionalArchivo::find($s_archivo->id);
      			}
      		}
      	}

      	$preocup_archivo->preocupacional_id = $doc->id;
      	$preocup_archivo->archivo = $doc->archivo;
      	$preocup_archivo->hash_archivo = $doc->hash_archivo;
      	$preocup_archivo->save();

      }
    }
}

<?php

namespace App\Service;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ImgCorridasDF
{
    public function mostrarImagem($id)
    {
        $corrida = DB::table('news_letter.corridas_df')->where('id', $id)->first();
        if (!$corrida || !$corrida->img) {
            abort(404);
        }

        $finfo = finfo_open();
        $mimeType = finfo_buffer($finfo, $corrida->img, FILEINFO_MIME_TYPE);
        finfo_close($finfo);

        return Response::make($corrida->img, 200, [
            'Content-Type' => $mimeType,
        ]);
    }
}

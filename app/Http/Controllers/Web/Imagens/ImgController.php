<?php

namespace App\Http\Controllers\Web\Imagens;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\ImgCorridasDF;

class ImgController extends Controller
{
    public function acessaImg(Request $request){
        $validated = $request->validate([
            'id' => 'required|integer',
            'codigo' => 'required|string',
        ]);

        switch ($validated['codigo']) {
            case 'corridas_df':
                $imgCorridasDF = new ImgCorridasDF();
                return $imgCorridasDF->mostrarImagem($validated['id']);

            default:
                return response()->json(['error' => 'Código de imagem não encontrado'], 404);
                break;
        }
    }
}

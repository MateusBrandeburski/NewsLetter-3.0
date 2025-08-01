<?php

namespace App\Http\Controllers\Web\Teste;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class TesteController extends Controller
{
    public function index()
    {
        return Inertia::render('Teste', [
            'mensagem' => 'Olá Mundo!'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Web\Atletismo;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CorridasDFController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');
        $page = $request->input('page', 1);

        $query = DB::table('news_letter.corridas_df')->orderBy('data_evento', 'asc');

        // Se existir um termo de busca
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'LIKE', "%{$search}%")
                  ->orWhere('distancia', 'LIKE', "%{$search}%")
                  ->orWhere('data_evento', 'LIKE', "%{$search}%")
                  ->orWhere('local', 'LIKE', "%{$search}%")
                  ->orWhere('horario', 'LIKE', "%{$search}%")
                  ->orWhere('valor', 'LIKE', "%{$search}%");
            });
        }

        $total = $query->count();

        // Paginação
        $offset = ($page - 1) * $perPage;
        $corridas = $query->offset($offset)->limit($perPage)->get();

        // Limpar dados
        $corridasLimpas = $corridas->map(function ($corrida) {
            $corridaArray = (array) $corrida;
            $corridaLimpa = [];
            
            foreach ($corridaArray as $key => $value) {
                if (is_string($value)) {
                    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
                    $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                }
                $corridaLimpa[$key] = $value;
            }
            
            return (object) $corridaLimpa;
        });

        return Inertia::render('atletismo/CorridasDF', [
            'corridas' => $corridasLimpas,
            'pagination' => [
                'current_page' => (int) $page,
                'per_page' => (int) $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage),
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $total)
            ],
            'filters' => [
                'search' => $search,
                'per_page' => $perPage
            ]
        ]);
    }
} 
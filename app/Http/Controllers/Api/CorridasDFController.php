<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        // Se existir um termo de busca, aplica um WHERE com LIKE em todas as colunas relevantes
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'LIKE', "%{$search}%")
                  ->orWhere('local', 'LIKE', "%{$search}%")
                  ->orWhere('data_evento', 'LIKE', "%{$search}%")
                  ->orWhere('descricao', 'LIKE', "%{$search}%");
            });
        }

        $total = $query->count();

        // Paginação
        $offset = ($page - 1) * $perPage;
        $corridas = $query->offset($offset)->limit($perPage)->get();

        // Tratar encoding e limpar dados para evitar problemas de caracteres especiais
        $corridasLimpas = $corridas->map(function ($corrida) {
            $corridaArray = (array) $corrida;
            $corridaLimpa = [];
            
            foreach ($corridaArray as $key => $value) {
                if (is_string($value)) {
                    // Limpar caracteres não imprimíveis e converter encoding
                    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
                    $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                }
                $corridaLimpa[$key] = $value;
            }
            
            return (object) $corridaLimpa;
        });

        return response()->json([
            'data' => $corridasLimpas,
            'current_page' => (int) $page,
            'per_page' => (int) $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
} 
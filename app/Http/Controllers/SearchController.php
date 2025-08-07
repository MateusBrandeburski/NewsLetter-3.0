<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    /**
     * Realizar busca mocada no Elasticsearch
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('query', '');
        
        // Validação básica
        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Digite pelo menos 2 caracteres para buscar',
                'results' => []
            ]);
        }

        // Dados mocados simulando resultados do Elasticsearch
        $mockResults = $this->getMockResults($query);

        return response()->json([
            'success' => true,
            'query' => $query,
            'total' => count($mockResults),
            'results' => $mockResults
        ]);
    }

    /**
     * Gerar resultados mocados baseados na query
     *
     * @param string $query
     * @return array
     */
    private function getMockResults(string $query): array
    {
        $allResults = [
            [
                'id' => 1,
                'title' => 'Dashboard Principal',
                'description' => 'Visão geral do sistema com métricas importantes',
                'url' => '/dashboard',
                'type' => 'page',
                'icon' => 'ti-dashboard'
            ],
            [
                'id' => 2,
                'title' => 'Usuários',
                'description' => 'Gerenciamento de usuários do sistema',
                'url' => '/users',
                'type' => 'page',
                'icon' => 'ti-users'
            ],
            [
                'id' => 3,
                'title' => 'Configurações',
                'description' => 'Configurações gerais do sistema',
                'url' => '/settings',
                'type' => 'page',
                'icon' => 'ti-settings'
            ],
            [
                'id' => 4,
                'title' => 'Relatórios',
                'description' => 'Relatórios e análises do sistema',
                'url' => '/reports',
                'type' => 'page',
                'icon' => 'ti-chart-pie'
            ],
            [
                'id' => 5,
                'title' => 'Atletismo - Corridas DF',
                'description' => 'Página de corridas do Distrito Federal',
                'url' => '/atletismo/corridas-df',
                'type' => 'page',
                'icon' => 'ti-run'
            ],
            [
                'id' => 6,
                'title' => 'Login',
                'description' => 'Página de autenticação do sistema',
                'url' => '/login',
                'type' => 'auth',
                'icon' => 'ti-login'
            ],
            [
                'id' => 7,
                'title' => 'Perfil do Usuário',
                'description' => 'Gerenciar informações do perfil',
                'url' => '/profile',
                'type' => 'user',
                'icon' => 'ti-user'
            ],
            [
                'id' => 8,
                'title' => 'Imagens',
                'description' => 'Galeria de imagens do sistema',
                'url' => '/images',
                'type' => 'media',
                'icon' => 'ti-photo'
            ]
        ];

        // Filtrar resultados baseado na query (busca insensível a maiúsculas/minúsculas)
        $filtered = array_filter($allResults, function($item) use ($query) {
            return stripos($item['title'], $query) !== false || 
                   stripos($item['description'], $query) !== false;
        });

        // Reordenar por relevância (títulos que começam com a query primeiro)
        usort($filtered, function($a, $b) use ($query) {
            $aStartsWithQuery = stripos($a['title'], $query) === 0 ? 1 : 0;
            $bStartsWithQuery = stripos($b['title'], $query) === 0 ? 1 : 0;
            
            return $bStartsWithQuery - $aStartsWithQuery;
        });

        // Limitar a 6 resultados
        return array_slice(array_values($filtered), 0, 6);
    }

    /**
     * Busca avançada com filtros
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function advancedSearch(Request $request): JsonResponse
    {
        $query = $request->input('query', '');
        $type = $request->input('type', '');
        $limit = $request->input('limit', 10);

        $results = $this->getMockResults($query);

        // Filtrar por tipo se especificado
        if (!empty($type)) {
            $results = array_filter($results, function($item) use ($type) {
                return $item['type'] === $type;
            });
        }

        // Aplicar limite
        $results = array_slice(array_values($results), 0, $limit);

        return response()->json([
            'success' => true,
            'query' => $query,
            'type' => $type,
            'total' => count($results),
            'results' => $results
        ]);
    }
} 
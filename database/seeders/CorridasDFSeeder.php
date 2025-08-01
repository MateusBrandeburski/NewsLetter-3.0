<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CorridasDFSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $corridas = [
            [
                'nome' => 'Corrida de Rua Brasília',
                'distancia' => '5km e 10km',
                'local' => 'Eixo Monumental, Brasília-DF',
                'valor' => 'R$ 80,00',
                'horario' => '07:00',
                'inscricao' => 'https://www.corridadebrasilia.com.br',
                'img' => null,
                'data_evento' => '2025-09-15',
            ],
            [
                'nome' => 'Maratona de Brasília',
                'distancia' => '21km e 42km',
                'local' => 'Estádio Nacional Mané Garrincha - Brasília-DF',
                'valor' => 'R$ 150,00',
                'horario' => '06:00',
                'inscricao' => 'https://www.maratonadebrasilia.com.br',
                'img' => null,
                'data_evento' => '2025-10-12',
            ],
            [
                'nome' => 'Corrida do Lago',
                'distancia' => '5km, 10km e 15km',
                'local' => 'Lago Paranoá - Brasília-DF',
                'valor' => 'R$ 65,00',
                'horario' => '06:30',
                'inscricao' => 'https://www.corridadolago.com.br',
                'img' => null,
                'data_evento' => '2025-08-20',
            ],
            [
                'nome' => 'Night Run Brasília',
                'distancia' => '5km e 10km',
                'local' => 'Torre de TV - Brasília-DF',
                'valor' => 'R$ 90,00',
                'horario' => '19:00',
                'inscricao' => 'https://www.nightrunbrasilia.com.br',
                'img' => null,
                'data_evento' => '2025-11-02',
            ],
            [
                'nome' => 'Corrida dos Ministérios',
                'distancia' => '3km, 5km e 10km',
                'local' => 'Esplanada dos Ministérios - Brasília-DF',
                'valor' => 'R$ 70,00',
                'horario' => '07:30',
                'inscricao' => 'https://www.corridaministerios.com.br',
                'img' => null,
                'data_evento' => '2025-09-25',
            ],
            [
                'nome' => 'Corrida Ecológica do Cerrado',
                'distancia' => '8km e 16km',
                'local' => 'Parque Nacional de Brasília - Brasília-DF',
                'valor' => 'R$ 85,00',
                'horario' => '06:45',
                'inscricao' => 'https://www.corridacerrado.com.br',
                'img' => null,
                'data_evento' => '2025-10-30',
            ],
            [
                'nome' => 'Corrida da Independência',
                'distancia' => '7km e 14km',
                'local' => 'Memorial JK - Brasília-DF',
                'valor' => 'R$ 75,00',
                'horario' => '07:00',
                'inscricao' => 'https://www.corridaindependencia.com.br',
                'img' => null,
                'data_evento' => '2025-09-07',
            ],
            [
                'nome' => 'Corrida Pink',
                'distancia' => '5km',
                'local' => 'Complexo Brasil 21 - Brasília-DF',
                'valor' => 'R$ 60,00',
                'horario' => '08:00',
                'inscricao' => 'https://www.corridapink.com.br',
                'img' => null,
                'data_evento' => '2025-10-05',
            ],
            [
                'nome' => 'Corrida do Descobrimento',
                'distancia' => '6km e 12km',
                'local' => 'Catedral Metropolitana - Brasília-DF',
                'valor' => 'R$ 80,00',
                'horario' => '06:30',
                'inscricao' => 'https://www.corridadescobrimento.com.br',
                'img' => null,
                'data_evento' => '2025-04-22',
            ],
            [
                'nome' => 'Corrida da Primavera',
                'distancia' => '4km, 8km e 12km',
                'local' => 'Jardim Botânico - Brasília-DF',
                'valor' => 'R$ 70,00',
                'horario' => '07:15',
                'inscricao' => 'https://www.corridaprimavera.com.br',
                'img' => null,
                'data_evento' => '2025-09-22',
            ],
            [
                'nome' => 'Corrida do Servidor',
                'distancia' => '5km e 10km',
                'local' => 'Congresso Nacional - Brasília-DF',
                'valor' => 'R$ 55,00',
                'horario' => '07:00',
                'inscricao' => 'https://www.corridaservidor.com.br',
                'img' => null,
                'data_evento' => '2025-10-28',
            ],
            [
                'nome' => 'Meia Maratona Internacional de Brasília',
                'distancia' => '21km',
                'local' => 'Ponte JK - Brasília-DF',
                'valor' => 'R$ 120,00',
                'horario' => '06:00',
                'inscricao' => 'https://www.meiamaratonabrasilia.com.br',
                'img' => null,
                'data_evento' => '2025-11-16',
            ],
            [
                'nome' => 'Corrida das Águas',
                'distancia' => '5km, 10km e 15km',
                'local' => 'Ponte do Bragueto - Brasília-DF',
                'valor' => 'R$ 65,00',
                'horario' => '06:30',
                'inscricao' => 'https://www.corridaaguas.com.br',
                'img' => null,
                'data_evento' => '2025-03-22',
            ],
            [
                'nome' => 'Corrida Noturna da Capital',
                'distancia' => '6km e 12km',
                'local' => 'Palácio da Alvorada - Brasília-DF',
                'valor' => 'R$ 85,00',
                'horario' => '19:30',
                'inscricao' => 'https://www.corridanoturnacapital.com.br',
                'img' => null,
                'data_evento' => '2025-12-07',
            ],
            [
                'nome' => 'Corrida do Planalto',
                'distancia' => '8km e 16km',
                'local' => 'Palácio do Planalto - Brasília-DF',
                'valor' => 'R$ 90,00',
                'horario' => '07:00',
                'inscricao' => 'https://www.corridaplanalto.com.br',
                'img' => null,
                'data_evento' => '2025-04-15',
            ]
        ];

        foreach ($corridas as $corrida) {
            DB::table('corridas_df')->insert([
                'nome' => $corrida['nome'],
                'distancia' => $corrida['distancia'],
                'local' => $corrida['local'],
                'valor' => $corrida['valor'],
                'horario' => $corrida['horario'],
                'inscricao' => $corrida['inscricao'],
                'img' => $corrida['img'],
                'data_evento' => $corrida['data_evento'],
                'data_cadastro' => Carbon::now(),
                'data_atualizacao' => Carbon::now(),
            ]);
        }
    }
}

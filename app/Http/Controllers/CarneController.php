<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Carne;
use Carbon\Carbon;

class CarneController extends Controller
{
    public function criarCarne(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'valor_total' => 'required|numeric|min:0.01',
            'qtd_parcelas' => 'required|integer|min:1',
            'data_primeiro_vencimento' => 'required|date',
            'periodicidade' => 'required|in:mensal,semanal',
            'valor_entrada' => 'nullable|numeric|min:0.01',
        ]);

        $carne = Carne::create($validated);
        $parcelas = $this->gerarParcelas($carne);

        return response()->json([
            'total' => $carne->valor_total,
            'valor_entrada' => $carne->valor_entrada,
            'parcelas' => $parcelas
        ]);
    }

    public function recuperarParcelas($id): JsonResponse
    {
        $carne = Carne::findOrFail($id);
        $parcelas = $this->gerarParcelas($carne);

        return response()->json($parcelas);
    }

    private function gerarParcelas($carne): array
    {
        $parcelas = [];
        $valor_restante = $carne->valor_total - $carne->valor_entrada;
        $valor_parcela = round($valor_restante / $carne->qtd_parcelas, 2);
        $data_vencimento = Carbon::parse($carne->data_primeiro_vencimento);

        if ($carne->valor_entrada) {
            $parcelas[] = [
                'data_vencimento' => $data_vencimento->format('Y-m-d'),
                'valor' => $carne->valor_entrada,
                'numero' => 1,
                'entrada' => true
            ];
            $data_vencimento = $this->calcularProximaData($data_vencimento, $carne->periodicidade);
        }

        for ($i = 1; $i <= $carne->qtd_parcelas; $i++) {
            $parcelas[] = [
                'data_vencimento' => $data_vencimento->format('Y-m-d'),
                'valor' => $valor_parcela,
                'numero' => $carne->valor_entrada ? $i + 1 : $i,
                'entrada' => false
            ];
            $data_vencimento = $this->calcularProximaData($data_vencimento, $carne->periodicidade);
        }

        return $parcelas;
    }

    private function calcularProximaData($data, $periodicidade)
    {
        if ($periodicidade == 'mensal') {
            return $data->addMonth();
        }
        return $data->addWeek();
    }
}

<?php
namespace App;

class GerenciadorPedidos
{
    private string $caminhoArquivo = 'pedidos.json';
    public function processarPedido(float $subtotal, string $cupom, string $dataPedido): array
    {
         $frete = 15.00;
        if ($subtotal > 100.00) { 
            $frete = 0.00;
        }

        $desconto = 0.00;
        if ($cupom === 'DESCONTO10') {
            $dataExpiracao = '2026-05-01';
            if ($dataPedido > $dataExpiracao) { 
                $desconto = $subtotal * 0.10;
            }
        }
        $total = $subtotal + $frete - $desconto;
        return [
            'subtotal' => $subtotal,
            'frete'    => $frete,
            'desconto' => $desconto,
            'total'    => $total
        ];
    }

    public function gravarPedido(array $pedido): bool
    {
        $json = json_encode($pedido);
        return file_put_contents($this->caminhoArquivo, $json, FILE_APPEND) !== false;
    }

    public function lerUltimoPedido(): ?array
    {
        if (!file_exists($this->caminhoArquivo)) {
            return null;
        }
        $conteudo = file_get_contents($this->caminhoArquivo);
        return json_decode($conteudo, true);
    }

    public function setCaminhoArquivo(string $caminho): void
    {
        $this->caminhoArquivo = $caminho;
    }
}

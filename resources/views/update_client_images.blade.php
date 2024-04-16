<?php

use App\Models\Cliente;
use Illuminate\Support\Str;

$clientes = Cliente::all();

foreach ($clientes as $cliente) {
    if ($cliente->img_cliente === null) {
        // Gere um nome de arquivo único usando UUID
        $uniqueFileName = Str::uuid()->toString() . '.png';

        // Caminho de origem da imagem
        $sourceImagePath = 'C:\Users\Programacao\Pictures\Saved Pictures' . DIRECTORY_SEPARATOR . $cliente->codigo_cliente . '.png';

        // Caminho de destino no diretório 'public/img_cliente' com o nome de arquivo único
        $destinationImagePath = public_path('img_cliente') . DIRECTORY_SEPARATOR . $uniqueFileName;

        if (file_exists($sourceImagePath)) {
            // Realize a cópia da imagem
            if (copy($sourceImagePath, $destinationImagePath)) {
                // Atualize o registro do cliente no banco de dados com o caminho da imagem
                $cliente->img_cliente = 'img_cliente/' . $uniqueFileName;
                $cliente->save();
            }
        }
    }
}


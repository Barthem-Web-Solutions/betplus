<?php

namespace App\Http\Middleware;

use Closure;

class BlockByIp
{
    public function handle($request, Closure $next)
    {
        // Lista de IPs permitidos
        $allowedIPs = [
            '208.68.39.149',
            '157.245.93.131',
            '162.243.162.250',
            '137.184.60.127',
            '192.34.62.86',
            '159.223.100.252',
         
        ];

        // Obtém o endereço IP do cliente
        $clientIP = $request->ip();

        // Verifica se o IP do cliente está na lista de IPs permitidos
        if (!in_array($clientIP, $allowedIPs)) {
            // Retorna uma resposta de erro ou redireciona para outra rota
            return response()->json(['error' => 'Forbidden'], 403);
        }

        // Se o IP do cliente estiver na lista de IPs permitidos, passe para o próximo middleware
        return $next($request);
    }
}

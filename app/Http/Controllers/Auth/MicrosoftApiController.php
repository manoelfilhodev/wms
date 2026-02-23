<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class MicrosoftApiController extends Controller
{
    public function loginFromApp(Request $request)
    {
        $request->validate([
            'microsoft_token' => 'required|string',
        ]);

        // Valida token da Microsoft e obt«±m dados do usu«¡rio
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $request->microsoft_token,
        ])->get('https://graph.microsoft.com/v1.0/me');

        if ($response->failed()) {
            return response()->json(['message' => 'Token Microsoft inv«¡lido'], 401);
        }

        $microsoftUser = $response->json();
        $email = $microsoftUser['mail'] ?? $microsoftUser['userPrincipalName'];
        $name = $microsoftUser['displayName'];

        // Procura usu«¡rio
        $user = DB::table('_tb_usuarios')->where('email', $email)->first();

        // Cria se n«ªo existir
        if (!$user) {
            $id_user = DB::table('_tb_usuarios')->insertGetId([
                'nome'        => $name,
                'email'       => $email,
                'password'    => bcrypt(str()->random(16)),
                'unidade_id'  => null,
                'tipo'        => 'operador',
                'status'      => 'ativo',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $user = DB::table('_tb_usuarios')->where('id_user', $id_user)->first();
        }

        // Gera token Sanctum usando o model User
        $eloquentUser = User::where('email', $email)->first();
        $token = $eloquentUser->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'    => $user->id_user,
                'nome'  => $user->nome,
                'email' => $user->email,
                'tipo'  => $user->tipo
            ]
        ]);
    }
}

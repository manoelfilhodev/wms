<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (empty($credentials['email']) || empty($credentials['password'])) {
            return back()->with('error', 'Preencha todos os campos.');
        }

        $usuario = User::where('email', $credentials['email'])->first();

        $logBase = [
            'usuario_id' => $usuario->id_user ?? null,
            'unidade_id' => $usuario->unidade_id ?? null,
            'dados'      => json_encode(['email' => $credentials['email']]),
            'ip_address' => $request->ip(),
            'navegador'  => $request->header('User-Agent'),
            'created_at' => now()
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            session([
                'user_id' => $usuario->id_user,
                'nome'    => $usuario->nome,
                'tipo'    => $usuario->tipo,
                'unidade' => $usuario->unidade_id,
                'nivel'   => $usuario->nivel
            ]);

            DB::table('_tb_user_logs')->insert(array_merge($logBase, [
                'acao' => 'login - sucesso'
            ]));

            return $usuario->tipo === 'operador'
                ? redirect()->route('painel.operador')
                : redirect()->intended('/dashboard');
        }

        DB::table('_tb_user_logs')->insert(array_merge($logBase, [
            'acao' => 'login - falhou'
        ]));

        return back()->with('error', 'Login ou senha inválido');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        DB::table('_tb_user_logs')->insert([
            'usuario_id' => $user->id_user,
            'unidade_id' => $user->unidade_id,
            'acao'       => 'logout',
            'dados'      => '[LOGOUT] - Usuário saiu manualmente do sistema.',
            'ip_address' => $request->ip(),
            'created_at' => now()
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Você saiu do sistema com sucesso!');
    }

    public function apiLogin(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (empty($credentials['email']) || empty($credentials['password'])) {
        return response()->json(['message' => 'Preencha todos os campos.'], 422);
    }

    $user = User::where('email', $credentials['email'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
        DB::table('_tb_user_logs')->insert([
            'usuario_id' => $user->id_user ?? null,
            'unidade_id' => $user->unidade_id ?? null,
            'acao'       => 'login_api - falhou',
            'dados'      => json_encode(['email' => $credentials['email']]),
            'ip_address' => $request->ip(),
            'navegador'  => $request->header('User-Agent'),
            'created_at' => now()
        ]);

        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }

    // 🔑 Gera token Sanctum
    $token = $user->createToken('app_token')->plainTextToken;

    // 🔹 Base do log (reutilizável)
    $logBase = [
        'usuario_id' => $user->id_user,
        'unidade_id' => $user->unidade_id,
        'dados'      => json_encode(['email' => $user->email]),
        'ip_address' => $request->ip(),
        'navegador'  => $request->header('User-Agent'),
        'created_at' => now()
    ];

    // 🔹 Registra login via API
    DB::table('_tb_user_logs')->insert(array_merge($logBase, [
        'acao' => 'login_app - sucesso'
    ]));

    // 🔹 Registra login geral
    DB::table('_tb_user_logs')->insert(array_merge($logBase, [
        'acao' => 'login - sucesso'
    ]));

    return response()->json([
        'token' => $token,
        'user'  => [
            'id'      => $user->id_user,
            'nome'    => $user->nome,
            'tipo'    => $user->tipo,
            'unidade' => $user->unidade_id,
            'nivel'   => $user->nivel
        ]
    ]);
}

}

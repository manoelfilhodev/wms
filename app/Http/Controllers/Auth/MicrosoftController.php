<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class MicrosoftController extends Controller
{
    public function redirectToProvider(): RedirectResponse
    {
        return Socialite::driver('microsoft')->redirect();
    }

    public function handleProviderCallback(Request $request): RedirectResponse
    {
        try {
            $microsoftUser = Socialite::driver('microsoft')->user();

            $email = $microsoftUser->getEmail();
            $name = $microsoftUser->getName() ?: 'Usuario Microsoft';

            if (! $email) {
                return redirect()->route('login')->with('error', 'Nao foi possivel obter o e-mail da conta Microsoft.');
            }

            $user = User::where('email', $email)->first();

            if (! $user) {
                $userId = DB::table('_tb_usuarios')->insertGetId([
                    'nome' => $name,
                    'email' => $email,
                    'password' => bcrypt(str()->random(32)),
                    'unidade_id' => 1,
                    'tipo' => 'operador',
                    'status' => 'ativo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $user = User::where('id_user', $userId)->first();
            }

            Auth::login($user);
            $request->session()->regenerate();

            DB::table('_tb_user_logs')->insert([
                'usuario_id' => $user->id_user,
                'unidade_id' => $user->unidade_id,
                'acao' => 'login_microsoft - sucesso',
                'dados' => json_encode(['email' => $email]),
                'ip_address' => $request->ip(),
                'navegador' => $request->userAgent(),
                'created_at' => now(),
            ]);

            return redirect()->intended('/dashboard');
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route('login')->with('error', 'Falha no login com Microsoft.');
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Lista todos os usuários

    public function index()
{
    // Supondo que você seja o login 'admin' ou tenha id_user = 1
    $usuarios = \DB::table('_tb_usuarios')
        ->where('email', '!=', 'admin')
        ->where('id_user', '>', 5)// ou ->where('id_user', '!=', 1)
        ->orderBy('nome')
        ->get();
        
        if (session('tipo') !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Acesso não autorizado!');
        }

        return view('usuarios.index', compact('usuarios'));
}

    // Mostra o formulário de criação
    public function create()
    {
        return view('usuarios.create');
    }

    // Salva novo usuário
    public function store(Request $request)
{
    $request->validate([
        'nome'       => 'required|string|max:255',
        'email'      => 'required|string|max:255|unique:_tb_usuarios,email',
        'senha'      => 'required|string|min:6',
        'unidade'    => 'required|string|max:255',
        'status'     => 'required|in:0,1',
        'cod_nivel'  => 'required|integer',
        'desc_nivel' => 'required|string|max:255',
    ]);

    // Criptografar senha
    $senhaCriptografada = md5($request->senha); // ou use bcrypt se preferir segurança maior

    // Inserir no banco
    \DB::table('_tb_users')->insert([
        'nome'       => $request->nome,
        'email'      => $request->email,
        'senha'      => $senhaCriptografada,
        'unidade'    => $request->unidade,
        'status'     => $request->status,
        'cod_nivel'  => $request->cod_nivel,
        'desc_nivel' => $request->desc_nivel,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('usuarios.index')->with('success', 'Usuário cadastrado com sucesso!');
}

public function edit($id)
{
    $usuario = \DB::table('_tb_usuarios')->where('id_user', $id)->first();

    if (!$usuario) {
        return redirect()->route('usuarios.index')->with('error', 'Usuário não encontrado.');
    }

    return view('usuarios.edit', compact('usuario'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'nome'       => 'required|string|max:255',
        'login'      => 'required|string|max:255',
        'unidade'    => 'required|string|max:255',
        'status'     => 'required|in:0,1',
        'cod_nivel'  => 'required|integer',
        'desc_nivel' => 'required|string|max:255',
    ]);

    \DB::table('_tb_users')->where('id_user', $id)->update([
        'nome'       => $request->nome,
        'login'      => $request->login,
        'unidade'    => $request->unidade,
        'status'     => $request->status,
        'cod_nivel'  => $request->cod_nivel,
        'desc_nivel' => $request->desc_nivel,
        'updated_at' => now(),
    ]);

    return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
}

public function destroy($id)
{
    \DB::table('_tb_users')->where('id_user', $id)->delete();

    return redirect()->route('usuarios.index')->with('success', 'Usuário excluído com sucesso!');
}


}

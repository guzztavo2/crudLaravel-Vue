<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    const validMessagesActions = [
        'senhaUsuario' =>
        [
            0 => 'senhaUsuario',
            1 => 'required|string|min:5|max:50',
            2 => [
                'string' => 'O tipo de informação tem de ser string.',
                'min' => 'Existe uma quantidade minima de 5 caracteres.',
                'max' => 'Existe uma quantidade máxima de 50 caracteres.',
                'required' => 'O campo senha de usuário é um requisito.'
            ]
        ],
        'nomeUsuario' =>
        [
            0 => 'nomeUsuario',
            1 => 'required|string|min:5|max:50',
            2 => [
                'string' => 'O tipo de informação tem de ser string.',
                'min' => 'Existe uma quantidade minima de 5 caracteres.',
                'max' => 'Existe uma quantidade máxima de 50 caracteres.',
                'required' => 'O campo nome do usuário é um requisito.',
                'unique' => 'O nome de usuário é único e esse informado já está registrado, por favor tente outro.'
            ]
        ]

    ];


    private static function redirecionarError(): User
    {
        if (Auth::hasUser() == false || Auth::user() == null)
            return response()->json([
                'errors' => 'Alguém acessou sua conta presentemente ou previamente, pois não conseguimos recuperar sua chave. Por favor, troque a senha ou logue novamente.',
            ], 422);

        $user = Auth::user();
        User::verificarDuplicidadeTokens($user);


        return $user;
    }
    public function verificarSenha(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                self::validMessagesActions['senhaUsuario'][0] => self::validMessagesActions['senhaUsuario'][1]
            ],
            self::validMessagesActions['senhaUsuario'][2]
        );
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
            ], 422);
        }
        $user = self::redirecionarError();

        if (Hash::check($request->senhaUsuario, $user->senhaUsuario)) {
            return response()->json(['sucesso, agora insira a nova senha.'], 200);
        } else {
            return response()->json([
                'errors' => 'A senha inserida não bate com a do usuário, por favor, tente novamente.',
            ], 422);
        }
    }
    public function verificarRegistroNomeUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            self::validMessagesActions['nomeUsuario'][0] => self::validMessagesActions['nomeUsuario'][1].'|unique:tb_user'
        ], self::validMessagesActions['nomeUsuario'][2]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
            ], 422);
        }
        return response()->json(['Tudo certo!'], 200);
    }
    public function registro(Request $request)
    {
        $validator = Validator::make($request->all(), [
            self::validMessagesActions['nomeUsuario'][0] => self::validMessagesActions['nomeUsuario'][1].'|unique:tb_user',
            self::validMessagesActions['senhaUsuario'][0] => self::validMessagesActions['senhaUsuario'][1]
        ], [

            self::validMessagesActions['senhaUsuario'][0] => self::validMessagesActions['senhaUsuario'][2],
            self::validMessagesActions['nomeUsuario'][0] =>  self::validMessagesActions['nomeUsuario'][2]

        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
            ], 422);
        }
        $user = User::gerarUsuario($request->nomeUsuario, $request->senhaUsuario);
        $user->save();
        return response()->json(['Usuário registrado com sucesso!'], 200);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            self::validMessagesActions['nomeUsuario'][0] => self::validMessagesActions['nomeUsuario'][1],
            self::validMessagesActions['senhaUsuario'][0] => self::validMessagesActions['senhaUsuario'][1]
        ], [

            self::validMessagesActions['senhaUsuario'][0] => self::validMessagesActions['senhaUsuario'][2],
            self::validMessagesActions['nomeUsuario'][0] =>  self::validMessagesActions['nomeUsuario'][2]

        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
            ], 422);
        }
        $user = User::buscarUsuario($request->nomeUsuario, $request->senhaUsuario);


        if ($user == null) {
            return response()->json([
                'errors' => 'Esse nome de usuário ou essa senha não existe: tente novamente ou recrie um usuário.',
            ], 422);
        }
        //Apenas um token por usuário para acessar a conta.
        $token = $user->criarToken();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
    public function user(Request $request)
    {
        $user = self::redirecionarError();
        return response()->json($user);
    }
    public function trocarSenha(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'senhaAntiga' => self::validMessagesActions['senhaUsuario'][1],
            'novaSenha' => self::validMessagesActions['senhaUsuario'][1],
            'repetirNovaSenha' => self::validMessagesActions['senhaUsuario'][1]
        ], self::validMessagesActions['senhaUsuario'][2]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
            ], 422);
        }
        $user = $request->user();
        if ($user->verificarSenha($request->senhaAntiga) == false)
            return response()->json(['errors' => 'A senha do usuário não é válida, tente novamente.'], 422);

        if (strcmp($request->novaSenha, $request->repetirNovaSenha) !== 0)
            return response()->json(['errors' => 'A senha não foi confirmada corretamente, por favor, tente novamente.'], 422);

        $user->trocarSenha($request->novaSenha);

        return response(200);
    }

    public function trocarNomeUsuario(Request $request){
        $validator = Validator::make($request->all(), [
            'nomeUsuario' => self::validMessagesActions['nomeUsuario'][1].'|unique:tb_user',
            'nomeUsuarioRepetir' => self::validMessagesActions['nomeUsuario'][1],
        ], self::validMessagesActions['nomeUsuario'][2]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
            ], 422);
        }
        $user = $request->user();

        if (strcmp($request->nomeUsuario, $request->nomeUsuarioRepetir) !== 0)
            return response()->json(['errors' => 'A confirmação de usuário não foram iguais, por favor, tente novamente.'], 422);

        $user->setNomeUsuario($request->nomeUsuario);
        $user->save();
        return response(200);
    }
}

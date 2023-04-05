<?php

namespace App\Http\Controllers;

use App\Models\Informacao;
use Illuminate\Http\Request;
use Nette\Utils\Json;

class informacaoController extends Controller
{
    public function login()
    {
    }
    public function registro()
    {
    }
    public function informacao(Request $request)
    {

        $onlyRequest = [
            'id', 'informacao', 'created_at', 'updated_at', 'page'
        ];
        $onlyValues = ['asc', 'desc'];
        $requestArray = [];
        $requestKey = array_keys($request->query->all())[0];
        if (in_array($requestKey, $onlyRequest)) {
            $requestvalue = array_values($request->query->all())[0];

            if (in_array($requestvalue, $onlyValues))
                Array_push($requestArray, $requestvalue, $onlyValues);
        }

        if ($request->buscar == null) {
            if (count($requestArray) === 0)
                return response()->json(Informacao::with('user')->where('isDeleted', false)->paginate(7), 200);
            else
                return response()->json(Informacao::with('user')->orderBy($requestKey, $requestvalue)->where('isDeleted', false)->paginate(7), 200);
        } else {
            $buscar = $request->buscar;
            if (count($requestArray) === 0){
                return response()->json(Informacao::with('user')
                ->orWhere('isDeleted', false)
                ->orWhere('informacao', 'like', "%" . $buscar . "%")
                ->orWhere('id', 'like', "%" . $buscar . "%")
                ->paginate(7), 200);
            }
            else
                return response()->json(Informacao::with('user')->orderBy($requestKey, $requestvalue)->orWhere('isDeleted', false)->paginate(7), 200);
        }
    }
    public function editarInformacao(Request $request, $id)
    {
        $validator = validator(
            $request->all(),
            ["informacao" => 'required|string|min:5|max:255'],
            [
                'required' => 'A informação é necessária para atualizar a informação',
                'string' => 'A informação é preciso estar em estado de caracteres para funcionar corretamente',
                'min' => 'O campo é preciso ter no minimo 5 caracteres',
                'max' => 'O campo é preciso ter no máximo :max caracteres.'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'erro' => $validator->errors()->all(),
            ], 422);
        }
        $informacao = self::buscarId($id);
        if ($informacao == null || $informacao->isDeleted == true)
            return response()->json(['erro' => 'Informação não foi localizada, provavelmente não existe ou já foi deletada!'], 404);

        $user = $request->user();
        if ($user == null)
            return response()->json(['erro' => 'O usuário não foi localizado, então não é possível editar essa informação.'], 404);

        $informacao->atualizarInformacao($informacao, $user);
        return response()->json(['sucesso' => 'Informação salva com sucesso'], 200);
    }
    public function buscarInformacao($id)
    {
        $informacao = self::buscarId($id);
        if ($informacao == null || $informacao->isDeleted == true)
            return response()->json(['erro' => 'Informação não foi localizada, provavelmente não existe ou já foi deletada!'], 404);
        return response()->json(['informacao' => $informacao, 'nomeUsuario' => $informacao->user->nomeUsuario], 200);
    }
    private static function buscarId($id)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $informacao = Informacao::find($id);
        return $informacao;
    }
    public function deletarInformacoes(Request $request)
    {

        $validate = validator(
            $request->all(),
            ["informacao" => 'required|array|min:1|max:10'],
            [
                'required' => 'A informação é necessária para atualizar a informação',
                'array' => 'É necessário ter várias informações, em forma de array para serem deletadas',
                'min' => 'É preciso ter no minimo :min elemento.',
                'max' => 'O é preciso ter no máximo :max elementos.'
            ]
        );


        dd($validate);
    }
    public function deletarInformacao(Request $request, $id)
    {
        $informacao = self::buscarId($id);
        if ($informacao == null || $informacao->isDeleted == true)
            return response()->json(['erro' => 'Informação não foi localizada, provavelmente não existe ou já foi deletada!'], 404);
        $informacao->isDeleted = true;
        $informacao->save();
        return response()->json(['sucesso' => 'Informação deletada com sucesso'], 200);
    }
}

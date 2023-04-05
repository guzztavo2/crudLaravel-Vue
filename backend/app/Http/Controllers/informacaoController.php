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
    private static function requestQuery(Request $request)
    {
        $onlyRequest = [
            'id',
            'informacao',
            'created_at',
            'updated_at'
        ];
        $onlyValues = ['asc', 'desc'];
        $requestKey = array_keys($request->query->all());
        $requestArray = [];

        $requestItem = '';
        foreach ($requestKey as $request_) {
            if (in_array($request_, $onlyRequest)) {
                $requestItem = $request_;
                break;
            }
        }

        if (strlen($requestItem) > 0 && in_array($request->query->all()[$requestItem], $onlyValues))
            array_push($requestArray, $requestItem, $request->query->all()[$requestItem]);

        return $requestArray;
    }
    public function informacao(Request $request, Informacao $informacao)
    {
        $requestArray = self::requestQuery($request);
        $buscar = $request->buscar;

        $query = $informacao::with('user')
            ->where('isDeleted', false)
            ->when($buscar, function ($query, $buscar) {
                return $query->where(function ($query) use ($buscar) {
                    $query->where('informacao', 'like', "%{$buscar}%")
                        ->orWhere('id', 'like', "%{$buscar}%");
                });
            });

        $query = $query->when(count($requestArray) > 0, function ($query) use ($requestArray) {
            return $query->orderBy($requestArray[0], $requestArray[1]);
        });

        return response()->json($query->paginate(7), 200);
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

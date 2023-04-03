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
            'id', 'informacao', 'created_at', 'updated_at','page'
        ];
        $onlyValues = ['asc', 'desc'];
        $requestKey = array_keys($request->query->all())[0];
        if (in_array($requestKey, $onlyRequest)) {
            $requestvalue = array_values($request->query->all())[0];
            if (in_array($requestvalue, $onlyValues)) {
                return response()->json(Informacao::with('user')->orderBy($requestKey, $requestvalue)->where('isDeleted', false)->paginate(7), 200);
            }
        }
        return response()->json(Informacao::with('user')->where('isDeleted', false)->paginate(7), 200);
    }
}

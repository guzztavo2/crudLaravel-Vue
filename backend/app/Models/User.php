<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nomeUsuario',
    ];
    protected $table = 'tb_user';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'senhaUsuario',
    ];

    public function informacao()
    {
        return $this->hasMany(Informacao::class);
    }

    public static function gerarUsuario(string $nomeUsuario, string $senhaUsuario)
    {
        $user = new User();
        $user->setNomeUsuario($nomeUsuario);
        $user->setSenhaUsuario($senhaUsuario);
        $user->save();

        return $user;
    }
    public static function buscarUsuario(string $nomeUsuario, string $senhaUsuario): user | null
    {
        $user = User::where('nomeUsuario', $nomeUsuario)->get();

        if (count($user->all()) > 0) {
            $user = $user[0];


            if ($user->verificarSenha($senhaUsuario))
                return $user;
        }
        return null;
    }

    public function verificarSenha(string $senha)
    {
        if (hash::check($senha, $this->getSenhaUsuario()))
            return true;
        return false;
    }
    public function setNomeUsuario(string $nomeUsuario)
    {
        $this->nomeUsuario = $nomeUsuario;
    }
    public function setSenhaUsuario(string $senhaUsuario)
    {
        $this->senhaUsuario = Hash::make($senhaUsuario);;
    }


    public function getNomeUsuario()
    {
        return $this->nomeUsuario;
    }
    public function getSenhaUsuario()
    {

        return $this->senhaUsuario;
    }
    public function quantidadeTokens()
    {
        return count($this->tokens()->get()->all());
    }
    public function removerToken()
    {
        $this->tokens()->get()->first()->delete();
    }
    public function criarToken()
    {
        if ($this->quantidadeTokens() > 0) {
            $this->removerToken();
        }
        return $this->createToken('userToken')->plainTextToken;
    }
    public function trocarSenha(string $novaSenha)
    {
        $this->setSenhaUsuario($novaSenha);
        $this->save();

    }
    public static function verificarDuplicidadeTokens(User $user)
    {
        if ($user->quantidadeTokens() > 1) {
            $user->removerToken();
        }
    }
}

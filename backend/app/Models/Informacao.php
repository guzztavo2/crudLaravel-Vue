<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informacao extends Model
{
    use HasFactory;
    protected $fillable = ['informacao', 'isDeleted'];
    protected $table = 'tb_informacao';

    public function user(){
        return $this->belongsTo(User::class);
    }
    public static function gerarInformacao(string $informacao, User $user){
        $info = new Informacao();
        $info->setInformacao($informacao);
        $info->setIsDeleted(false);
        $info->user()->save($user);
        //$user->informacao()->save($info);
    }
    public function atualizarInformacao(string $novaInformacao, User $user){
        if($this->getIsDeleted()){
            return false;
        }
        $this->setinformacao($novaInformacao);
        $this->user()->save($user);
    }

    public function getIsDeleted():bool{
        return $this->isDeleted;
    }
    public function setIsDeleted(bool $value){
        $this->isDeleted = $value;
    }
    public function setInformacao(string $informacao){
        $this->informacao = $informacao;
    }

}

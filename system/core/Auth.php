<?php
//gerenciamento da autenticacao
class AuthGuard {

    public static function authenticate($token){
        //valida o token se é válido ou não
    }

    public static function getUserId(){
        return ssession('user') ?: false;
    }
}
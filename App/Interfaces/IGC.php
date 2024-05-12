<?php
namespace App\Interfaces;

#Interface GerenciarContacto (IGC)
interface IGC{
	function adicionarContacto():bool;
	function editarContacto():bool;
	function excluirContacto():bool;
	function buscarContacto(string$nome,bool$type):array;
	function listarContacto(int$offset):array;
}

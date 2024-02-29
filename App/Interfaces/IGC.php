<?php
namespace App\Interfaces;

#Interface GerenciarContacto (IGC)
interface IGC{
	function adicionarContacto($actt):bool;
	function editarContacto($ectt):bool;
	function excluirContacto($exctt):bool;
	function buscarContacto():array;
	function listarContacto():array;
}

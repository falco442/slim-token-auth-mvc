<?php

namespace App\Controller;

class UsersController extends Controller{
	public function index($request,$response,$args){
		return $response->withJSON($this->table->get());
	}

	public function view($request,$response,$args){
		return $response->withJSON($this->table->get($args[0]));
	}

	public function add($request,$response,$args){

	}

	public function delete($request,$response,$args){

	}

	public function login($request,$response,$args){

	}
}
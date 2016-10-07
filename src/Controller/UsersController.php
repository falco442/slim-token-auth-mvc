<?php

namespace App\Controller;

class UsersController extends Controller{
	public function index($request,$response,$args){
		return $response->withJSON($this->table->get());
	}

	public function view($request,$response,$args){
		return $response->withJSON($this->table->find($args['id']));
	}

	public function add($request,$response,$args){

	}

	public function delete($request,$response,$args){

	}

	public function login($request,$response,$args){

	}
}
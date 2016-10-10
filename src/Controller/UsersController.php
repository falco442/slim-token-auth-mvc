<?php

namespace App\Controller;

use Slim\Middleware\Auth\TokenAuth;

class UsersController extends Controller{

	public function index($request,$response,$args){
		return $response->withJSON($this->table->get());
	}

	public function view($request,$response,$args){
		return $response->withJSON($this->table->find($args['id']));
	}

	public function add($request,$response,$args){
		$body = $request->getParsedBody();
		$body['password'] = $this->Auth->hash($body['password']);
		if($this->table->insert($body)){
			$response->withStatus(200)->withJSON("User added");
		}
		else{
			$response->withStatus(500)->withJSON("User not added");
		}
		return $response;
	}

	public function delete($request,$response,$args){

	}


	public function login($request,$response,$args){
		return $response->withJSON($this->Auth->authenticate($request));
	}

}
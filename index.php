<?php
	require_once 'Slim/Slim/Slim.php';
	require_once 'Mongo/MongoEngine.php';
	require_once 'Mongo/MongoConf.php';
	\Slim\Slim::registerAutoloader();

	$app   = new \Slim\Slim();
	$mongo = new MongoEngine($user, $password, $ip, $app);

	$validateMongo = function(\Slim\Route $route) use($app, $mongo){
		$params = $route->getParams();
		if(!$mongo->getStatus())
			$app->halt(400, 'Mongo connection failed');

		if(isset($params['id'])){
			 if (!preg_match('/^[0-9a-z]{24}$/', $params['id']))
			 	$app->halt(400, 'Invalide mongo ID');
		}
	};

	#id => document's MONGO ID
	$app->get('/:db/:collection(/)(:id)', $validateMongo, function($db, $collection, $id = null) use($app, $mongo){
		$app->response()->header('Content-Type', 'application/json');

		$mongo->setDb($db);
		$return   = array();
		$param    = isset($id)? array('_id' => new MongoId($id)) : array();
		$conn     = $mongo->selectCollection($collection);
		$produtos = $conn->find($param);
		$count    = $produtos->count();

		if($count){
			$return = array();
			foreach ($produtos as $key => $object) 
				array_push($return, $object);

			$return = (isset($id))? $return[0] : $return;

			$app->response()->write(json_encode($return));
			$status = 200;
		} else {
			$status = 204;
		}
		$app->response()->setStatus($status);
		$app->response()->finalize();
	});

	$app->post('/:db/:collection(/)', function($db, $collection) use($app, $mongo){
		$app->response()->header('Content-Type', 'application/json');

		$mongo->setDb($db);
		$conn  = $mongo->selectCollection($collection);
		$dados = $app->request->post();

		if($conn->insert($dados)){
					$reg = $conn->findOne($dados);
					if(is_array($reg) && isset($reg['_id'])) 
						$app->response()->write(json_encode($reg));

					$status = 200;
		} else {
			$status = 500;
		}

		$app->response()->setStatus($status);
		$app->response()->finalize();
	});

	#replace data completely
	#id => document's MONGO ID
	$app->put('/:db/:collection/:id(/)', $validateMongo, function($db, $collection, $id) use($app, $mongo){
		$app->response()->header('Content-Type', 'application/json');

		$mongo->setDb($db);
		$conn   = $mongo->selectCollection($collection);
		$dados  = $app->request->params();
		unset($dados['uri']);

		if($conn->update(array('_id' => new MongoId($id)), $dados)){
			$app->response()->write(json_encode(array("message" => "PUT OK", 'result' => $conn->findOne(array('_id' => new MongoId($id))))));
			$status = 200;
		} else {
			$status = 500;
		}

		$app->response()->setStatus($status);
		$app->response()->finalize();
	});

	#partially replace data
	#id => mdocument's MONGO ID
	$app->patch('/:db/:collection/:id(/)', $validateMongo, function($db, $collection, $id) use($app, $mongo){
		$app->response()->header('Content-Type', 'application/json');

		$mongo->setDb($db);
		$conn   = $mongo->selectCollection($collection);
		$dados  = $app->request->params();
		unset($dados['uri']);

		if($conn->update(array("_id" => new MongoId($id)), array( '$set' => $dados))){
			$app->response()->write(json_encode(array("message" => "PATCH OK", 'result' => $conn->findOne(array('_id' => new MongoId($id))))));
			$status = 200;
		} else {
			$status = 500;
		}

		$app->response()->setStatus($status);
		$app->response()->finalize();
	});

	$app->delete('/:db/:collection(/)', $validateMongo, function($db, $collection) use($app, $mongo){
		$app->response()->header('Content-Type', 'application/json');

		$mongo->setDb($db);
		$conn   = $mongo->selectCollection($collection);
		$dados  = $app->request->params();

		$conn->remove(array('_id' => new MongoId($dados['_id'])), true);

		$app->response()->write(json_encode(array("message" => "DELETE OK")));
		$app->response()->setStatus(200);
		$app->response()->finalize();
	});

	$app->notFound(function () use ($app) {
		$app->halt(404, 'Something went wrong...');
	});

	$app->run();
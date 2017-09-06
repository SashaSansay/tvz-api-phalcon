<?

use Phalcon\Mvc\Micro;
use Phalcon\Cache\Frontend\Data as FrontData;
use Phalcon\Cache\Backend\Libmemcached as BackMemCached;

require __DIR__.'/validation.php';
require __DIR__.'/response.php';

class App extends Micro
{
    private $validation;
    private $response;
//    private $url;

    private $cache;

    function __construct($dependencyInjector = null)
    {
        parent::__construct($dependencyInjector);

        $this->validation = new TValidation();
        $this->response = new TResponse();

        $frontCache = new FrontData(
            [
                'lifetime' => 60,
            ]
        );

        $this->cache = new BackMemCached(
            $frontCache,
            [
                'servers' => [
                    [
                        'host'   => '127.0.0.1',
                        'port'   => '11211',
                        'weight' => '1',
                    ]
                ]
            ]
        );

        $this->registerApp();
    }

    private function registerApp(){
        $this->get('/', function () {
            return $this->response->sendResponse(['hello' => true]);
        });

        $this->get('/category', function () {
            $cacheKey = $this->request->getURI();
            $cache = $this->checkCache($cacheKey);
            if($cache !== false){
                return $this->response->sendResponse($cache);
            }

            $res = Category::find();

            $resAr = [];

            foreach ($res as $item) {
                $resAr[] = $item->getForApi();
            }

            $this->cache->save($cacheKey,$resAr);

            return $this->response->sendResponse($resAr);
        });

        $this->get('/category/{id}', function ($id) {
            $cacheKey = $this->request->getURI();
            $cache = $this->checkCache($cacheKey);
            if($cache !== false){
                return $this->response->sendResponse($cache);
            }

            $errorsAr = $this->validation->validate(['id' => $id]);

            if(sizeof($errorsAr)>0){
                return $this->response->sendResponse([], $errorsAr);
            }

            $res = Category::findFirst($id);

            if ($res === false) {
                $error = [
                    'message' => 'Category not found',
                    'field' => 'id',
                ];
                return $this->response->sendResponse([], [
                    $error
                ]);
            }

            $resAr = $res->getForApi();

            $this->cache->save($cacheKey,$resAr);

            return $this->response->sendResponse($resAr);
        });

        $this->get('/serial',function(){
            $cacheKey = $this->request->getURI();
            $cache = $this->checkCache($cacheKey);
            if($cache !== false){
                return $this->response->sendResponse($cache);
            }

            $res = Serial::find();

            $resAr = [];

            foreach ($res as $item) {
                $resAr[] = $item->getForApi();
            }

            $this->cache->save($cacheKey,$resAr);

            return $this->response->sendResponse($resAr);
        });

        $this->get('/serial/{id}', function ($id) {
            $cacheKey = $this->request->getURI();
            $cache = $this->checkCache($cacheKey);
            if($cache !== false){
                return $this->response->sendResponse($cache);
            }

            $errorsAr = $this->validation->validate(['id' => $id]);

            if(sizeof($errorsAr)>0){
                return $this->response->sendResponse([], $errorsAr);
            }

            $res = Serial::findFirst($id);

            if ($res === false) {
                $error = [
                    'message' => 'Serial not found',
                    'field' => 'id',
                ];
                return $this->response->sendResponse([], [
                    $error
                ]);
            }

            $resAr = $res->getForApi();

            $this->cache->save($cacheKey,$resAr);

            return $this->response->sendResponse($resAr);
        });

        $this->get('/film',function(){
            $cacheKey = $this->request->getURI();
            $cache = $this->checkCache($cacheKey);
            if($cache !== false){
                return $this->response->sendResponse($cache);
            }

            $res = Film::find();

            $resAr = [];

            foreach ($res as $item) {
                $resAr[] = $item->getForApi();
            }

            $this->cache->save($cacheKey,$resAr);

            return $this->response->sendResponse($resAr);
        });

        $this->get('/film/{id}', function ($id) {
            $cacheKey = $this->request->getURI();
            $cache = $this->checkCache($cacheKey);
            if($cache !== false){
                return $this->response->sendResponse($cache);
            }

            $errorsAr = $this->validation->validate(['id' => $id]);

            if(sizeof($errorsAr)>0){
                return $this->response->sendResponse([], $errorsAr);
            }

            $res = Film::findFirst($id);

            if ($res === false) {
                $error = [
                    'message' => 'Film not found',
                    'field' => 'id',
                ];
                return $this->response->sendResponse([], [
                    $error
                ]);
            }

            $resAr = $res->getForApi();

            $this->cache->save($cacheKey,$resAr);

            return $this->response->sendResponse($resAr);
        });

        $this->get('/series/{id}', function ($id) {
            $cacheKey = $this->request->getURI();
            $cache = $this->checkCache($cacheKey);
            if($cache !== false){
                return $this->response->sendResponse($cache);
            }

            $errorsAr = $this->validation->validate(['id' => $id]);

            if(sizeof($errorsAr)>0){
                return $this->response->sendResponse([], $errorsAr);
            }

            $res = Series::findFirst($id);

            if ($res === false) {
                $error = [
                    'message' => 'Series not found',
                    'field' => 'id',
                ];
                return $this->response->sendResponse([], [
                    $error
                ]);
            }

            $resAr = $res->getForApi();

            $this->cache->save($cacheKey,$resAr);

            return $this->response->sendResponse($resAr);
        });

        $this->notFound(function () {
            $this->response->setStatusCode(404, "Not Found")->sendHeaders();
            require __DIR__ . "/../views/404.phtml";
        });
    }

    private function checkCache($key){
        $cache = $this->cache->get($key);

        if($cache === null){
            return false;
        }

        return $cache;
    }

}
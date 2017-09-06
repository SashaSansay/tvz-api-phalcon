<?

use Phalcon\Http\Response;

class TResponse extends Response{

    function __construct($content = null, $code = null, $status = null)
    {
        parent::__construct($content, $code, $status);
    }

    public function sendResponse($array, $errors = []){
        $responseAr = [
            'request' => [],
            'result' => []
        ];
        $response = new Response();

        $response->setHeader("Content-Type", "application/json");

        if(!isset($array['success'])){
            $responseAr['request']['success'] = true;
            $responseAr['request']['errors'] = [];
        }
        if(sizeof($errors) > 0){
            $responseAr['request']['success'] = false;
            $responseAr['request']['errors'] = $errors;
            $response->setStatusCode(400);
        }

        $responseAr['result'] = $array;

//        $response->setJsonContent($responseAr);

        $response->setContent(json_encode($responseAr, JSON_UNESCAPED_UNICODE));

        return $response;
    }
}
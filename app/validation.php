<?
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Numericality;


class TValidation{
    private $validation;

    function __construct()
    {
        $this->validation = new \Phalcon\Validation();
        $this->validation->add(
            'id',
            new PresenceOf(
                [
                    'message' => 'The id is required',
                ]
            )
        );

        $this->validation->add(
            'id',
            new Numericality(
                [
                    'message' => ':field is not valid',
                ]
            )
        );
    }

    private function checkForError($errors){
        if(count($errors)>0){
            $errorsAr = [];

            foreach ($errors as $message) {
                $errorsAr[] =
                    [
                        'message' => $message->getMessage(),
                        'field' => $message->getField(),
                    ];
            }

            return $errorsAr;
        }else{
            return [];
        }
    }

    public function validate($ar){
        return $this->checkForError($this->validation->validate($ar));
    }

}
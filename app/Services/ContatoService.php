<?php


namespace App\Services;


use App\Constants\Attribute;
use App\Constants\Messages;
use App\Models\Contato;

class ContatoService extends Service
{

    /**
     * ContatoService constructor.
     * @param Contato $model
     */
    public function __construct(Contato $model)
    {
        $this->model = $model;

        $this->queryBuilder = $this->model->with(Attribute::PESSOA);
        parent::__construct();
    }

    public function isValid(&$data, $id = null): bool
    {
        if(!$this->isPhone($data[Attribute::IC_CONTATO]) && !$this->isMail($data[Attribute::IC_CONTATO])){
            throw new \InvalidArgumentException(Messages::MSG002, 422);
        }
        if($this->isPhone($data[Attribute::IC_CONTATO])){
            $this->isPhoneValid($data);
        }

        if($this->isMail($data[Attribute::IC_CONTATO])) {
            $this->isEmailValid($data);
        }
        return true;
    }

    private function isPhoneValid(array  $data): void
    {
        if(!isset($data[Attribute::NR_DDD])) {
            throw new \InvalidArgumentException(Messages::MSG003, 422);
        }

        if(strlen($data[Attribute::NR_DDD])  !==  3){
            throw new \InvalidArgumentException(Messages::MSG004, 422);
        }

        if(!isset($data[Attribute::NR_TELEFONE])) {
            throw new \InvalidArgumentException(Messages::MSG005, 422);
        }


        if(($data[Attribute::IC_CONTATO] === Attribute::CELULAR) && (strlen($data[Attribute::NR_TELEFONE]) !== 9)){
            throw new \InvalidArgumentException(Messages::MSG006, 422);
        }

        if(($data[Attribute::IC_CONTATO] !== Attribute::CELULAR) && strlen($data[Attribute::NR_TELEFONE]) !== 8){
            throw new \InvalidArgumentException(Messages::MSG007, 422);
        }

        if(isset($data[Attribute::ED_EMAIL])){
            throw new \InvalidArgumentException(Messages::MSG008, 422);
        }
    }

    private function isEmailValid(array $data): void
    {
        if(!isset($data[Attribute::ED_EMAIL])) {
            throw new \InvalidArgumentException(Messages::MSG009, 422);
        }

        if(isset($data[Attribute::NR_DDD])){
            throw new \InvalidArgumentException(Messages::MSG010, 422);
        }

        if(isset($data[Attribute::NR_TELEFONE])){
            throw new \InvalidArgumentException(
                Messages::MSG011,
                422
            );
        }
    }

    public function save($data, $id = null)
    {
        try{
            $this->isValid($data, $id);
            return parent::save($data, $id);
        } catch (\Exception $exception) {
            return $exception;
        }


    }

    private function isPhone($type): bool
    {
        switch ($type) {
            case Attribute::CELULAR:
                $result =  true;
                break;
            case Attribute::COMERCIAL:
                $result =  true;
                break;
            case Attribute::RESIDENCIAL:
                $result =  true;
                break;
            default:
                $result =  false;
                break;
        }
        return $result;
    }

    private function isMail($type): bool
    {
        if($type === Attribute::EMAIL) {
            return true;
        }
        return false;
    }
}
<?php
namespace Dptsi\EsignBsre\Response;

class EsignBsreResponse
{
    private $status;
    private $errors;
    private $data;
    private $id_dokumen;
    private $response;
    const STATUS_OK = 200;
    const STATUS_TIMEOUT = 408;
    const WRONG_PASSPHRASE = 2031;

    public function setFromExeption($error, $status){
        $this->status = $status;
        $this->errors = $error->getMessage();
        $this->response = $error->getResponse();
        if (strpos(strtolower(implode(" ", $error->getResponse()->getHeader('Content-Type'))), strtolower('application/json')) !== false){
            $this->data = json_decode($error->getResponse()->getBody()->getContents());
        }else{
            $this->data = $this->response->getBody()->getContents();
        }

        return $this;
    }

    public function setFromResponse($response){
        $this->response = $response;
        $this->setStatusFromResponse();
        $this->setDataFromResponse();
        $this->setErrorsFromResponse();
        $this->setIdDokumenFromResponse();

        return $this;
    }

    private function setStatusFromResponse()
    {
        $this->status = $this->response->getStatusCode();
    }

    /**
     * @return mixed
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param mixed $errors
     */
    public function setErrorsFromResponse()
    {
        if ($this->isFailed()){
            $responseBody = json_decode($this->response->getBody()->getContents());

            $this->errors = $responseBody->message ?? $responseBody->error;
        }
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param mixed $data
     */
    public function setDataFromResponse()
    {
        if ($this->isSuccess()){
            if (strpos(strtolower(implode(" ", $this->response->getHeader('Content-Type'))), strtolower('application/json')) !== false)
                $this->data = json_decode($this->response->getBody()->getContents());
            else
                $this->data = $this->response->getBody()->getContents();
        }
    }

    /**
     * @param mixed $id_dokumen
     */
    public function setIdDokumenFromResponse()
    {
        if ($this->isSuccess()){
            if (!empty($this->response->getHeader('id_dokumen')[0]))
                $this->id_dokumen = $this->response->getHeader('id_dokumen')[0];
            else
                $this->id_dokumen = null;
        }else{
            $this->id_dokumen = null;
        }
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public function getIdDokumen()
    {
        return $this->id_dokumen;
    }

    public function isSuccess(){
        return $this->status == self::STATUS_OK;
    }

    public function isFailed(){
        return $this->status != self::STATUS_OK;
    }

    public function isWrongPassphrase(){
        if(!empty($this->data)){
            return $this->data->status_code == self::WRONG_PASSPHRASE;
        }else{
            return false;
        }
    }
}
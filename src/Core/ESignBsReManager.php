<?php
namespace Dptsi\ESignBSrE\Core;

use Dptsi\ESignBSrE\Response\ESignBsreResponse;
use Dptsi\ESignBSrE\Exception\InvalidArgument;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use DateTime;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;

class ESignBsReManager 
{
    private $http;
    private $timeout;

    public function __construct($timeout = 30)
    {
        $this->http = new Client();
        $this->timeout = $timeout; 
    }

    private function getAuth(){
        return [config('bsre.username'), config('bsre.password')];
    }

    private function getBaseUrl(){
        return rtrim(config('bsre.bsre_esign_uri'), "/");
    }

    public function cekStatusUser(string $nik){
        try {
            $response = $this->http->request('GET', "{$this->getBaseUrl()}/api/user/status/$nik", [
                'auth' => $this->getAuth(),
                'timeout' => $this->timeout,
            ]);
        } catch(\GuzzleHttp\Exception\ConnectException $e){
            return (new ESignBsreResponse())->setFromExeption($e, ESignBsreResponse::STATUS_TIMEOUT);
        } catch (\Exception $e) {
            return (new ESignBsreResponse())->setFromExeption($e, $e->getCode());
        }

        return (new ESignBsreResponse())->setFromResponse($response); 
    }

    public function sign($file, string $nik, string $passphrase){
        if($file instanceof UploadedFile) {
            $filename_extension = $file->getClientOriginalName();

            $original_name = pathinfo($filename_extension, PATHINFO_FILENAME);
            $original_name = preg_replace("/[^a-zA-Z0-9]+/", "", $original_name);
            if ($original_name == '') {
                $original_name = 'undefined' . time();
            }

            $filename = $file->hashName();
            $datafile = file_get_contents($file);

        } elseif ($file instanceof File) {
            $original_name = pathinfo($file->path(), PATHINFO_FILENAME);
            $original_name = preg_replace("/[^a-zA-Z0-9]+/", "", $original_name);
            if ($original_name == '') {
                $original_name = 'undefined' . time();
            }

            $filename = $file->hashName();

            $datafile = file_get_contents($file);

        } else {
            throw new InvalidArgument('Unsupported argument type.');
        }

        try {
            $response = $this->http->request('POST', "{$this->getBaseUrl()}/api/sign/pdf", [
                'auth' => $this->getAuth(),
                'timeout' => $this->timeout,
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => $datafile,
                        'filename' => $filename
                    ],
                    [
                        'name'     => 'nik',
                        'contents' => $nik
                    ],
                    [
                        'name'     => 'passphrase',
                        'contents' => $passphrase
                    ],
                    [
                        'name'     => 'tampilan',
                        'contents' => 'invisible'
                    ],
                ]
                ]);
        } catch (ServerException $e){
            return (new ESignBsreResponse())->setFromExeption($e, $e->getCode());
        } catch (\Exception $th) {
            return (new ESignBsreResponse())->setFromExeption($th, $th->getCode());
        }

    }

    public function signVisibleWithSpesimen($file, string $nik, string $passphrase, $image_ttd, int $page, int $x, int $y, int $width, int $height){

    }

    public function signVisibleWithQrCode($file, string $nik, string $passphrase, string $link_qrcode, int $page, int $x, int $y, int $width, int $height){

    }
}
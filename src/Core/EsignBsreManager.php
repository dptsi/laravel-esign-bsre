<?php
namespace Dptsi\EsignBsre\Core;

use Dptsi\EsignBsre\Response\EsignBsreResponse;
use Dptsi\EsignBsre\Exception\InvalidArgument;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use DateTime;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;

class EsignBsreManager 
{
    private $http;
    private $timeout;

    public function __construct($timeout = 60)
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
            return (new EsignBsreResponse())->setFromResponse($response); 

        } catch(\GuzzleHttp\Exception\ConnectException $e){
            return (new EsignBsreResponse())->setFromExeption($e, EsignBsreResponse::STATUS_TIMEOUT);
        } catch (\Exception $e) {
            return (new EsignBsreResponse())->setFromExeption($e, $e->getCode());
        }
    }

    public function sign(string $file_path, string $nik, string $passphrase){
        try {
            $response = $this->http->request('POST', "{$this->getBaseUrl()}/api/sign/pdf", [
                'auth' => $this->getAuth(),
                'timeout' => $this->timeout,
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($file_path, 'r')
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
            
                return (new EsignBsreResponse())->setFromResponse($response); 
        } catch (ServerException $e){
            return (new EsignBsreResponse())->setFromExeption($e, $e->getCode());
        } catch (\Exception $th) {
            return (new EsignBsreResponse())->setFromExeption($th, $th->getCode());
        }
    }

    public function signVisibleWithSpesimen(string $file_path, string $nik, string $passphrase, string $image_ttd_path, int $page, int $x, int $y, int $width, int $height){
        try {
            $response = $this->http->request('POST', "{$this->getBaseUrl()}/api/sign/pdf", [
                'auth' => $this->getAuth(),
                'timeout' => $this->timeout,
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($file_path, 'r')
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
                        'contents' => 'visible'
                    ],
                    [
                        'name'     => 'image',
                        'contents' => true
                    ],
                    [
                        'name'     => 'imageTTD',
                        'contents' => $image_ttd_path
                    ],
                    [
                        'name'     => 'page',
                        'contents' => $page
                    ],
                    [
                        'name'     => 'xAxis',
                        'contents' => $x
                    ],
                    [
                        'name'     => 'yAxis',
                        'contents' => $y
                    ],
                    [
                        'name'     => 'width',
                        'contents' => $width
                    ],
                    [
                        'name'     => 'height',
                        'contents' => $height
                    ],
                ]
                ]);
            
                return (new EsignBsreResponse())->setFromResponse($response); 
        } catch (ServerException $e){
            return (new EsignBsreResponse())->setFromExeption($e, $e->getCode());
        } catch (\Exception $th) {
            return (new EsignBsreResponse())->setFromExeption($th, $th->getCode());
        }
    }

    public function signVisibleWithQrCode(string $file_path, string $nik, string $passphrase, string $link_qrcode, int $page, int $x, int $y, int $width, int $height){
        try {
            $response = $this->http->request('POST', "{$this->getBaseUrl()}/api/sign/pdf", [
                'auth' => $this->getAuth(),
                'timeout' => $this->timeout,
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($file_path, 'r')
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
                        'contents' => 'visible'
                    ],
                    [
                        'name'     => 'image',
                        'contents' => false
                    ],
                    [
                        'name'     => 'linkQR',
                        'contents' => $link_qrcode
                    ],
                    [
                        'name'     => 'page',
                        'contents' => $page
                    ],
                    [
                        'name'     => 'xAxis',
                        'contents' => $x
                    ],
                    [
                        'name'     => 'yAxis',
                        'contents' => $y
                    ],
                    [
                        'name'     => 'width',
                        'contents' => $width
                    ],
                    [
                        'name'     => 'height',
                        'contents' => $height
                    ],
                ]
                ]);
            
                return (new EsignBsreResponse())->setFromResponse($response); 
        } catch (ServerException $e){
            return (new EsignBsreResponse())->setFromExeption($e, $e->getCode());
        } catch (\Exception $th) {
            return (new EsignBsreResponse())->setFromExeption($th, $th->getCode());
        }
    }
}
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

    public function sign($file, string $nik, string $passphrase){
        if($file instanceof UploadedFile || $file instanceof File) {
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
            
                return (new EsignBsreResponse())->setFromResponse($response); 
        } catch (ServerException $e){
            return (new EsignBsreResponse())->setFromExeption($e, $e->getCode());
        } catch (\Exception $th) {
            return (new EsignBsreResponse())->setFromExeption($th, $th->getCode());
        }
    }

    public function signVisibleWithSpesimen($file, string $nik, string $passphrase, $image_ttd, int $page, int $x, int $y, int $width, int $height){
        if($file instanceof UploadedFile || $file instanceof File) {
            $filename = $file->hashName();
            $datafile = file_get_contents($file);
        } else {
            throw new InvalidArgument('Unsupported argument type.');
        }

        if($image_ttd instanceof UploadedFile || $image_ttd instanceof File) {
            $ttd_filename = $file->hashName();
            $ttd_datafile = file_get_contents($image_ttd);
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
                        'contents' => 'visible'
                    ],
                    [
                        'name'     => 'image',
                        'contents' => true
                    ],
                    [
                        'name'     => 'imageTTD',
                        'contents' => $ttd_datafile,
                        'filename' => $ttd_filename
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

    public function signVisibleWithQrCode($file, string $nik, string $passphrase, string $link_qrcode, int $page, int $x, int $y, int $width, int $height){
        if($file instanceof UploadedFile || $file instanceof File) {
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
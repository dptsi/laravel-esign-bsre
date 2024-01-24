<?php
namespace Dptsi\EsignBsre\Facade;


use Illuminate\Support\Facades\Facade;

/**
 * Class EsignBsre
 * @package Dptsi\EsignBsre\Facade
 * @method static mixed cekStatusUser(string $nik)
 * @method static mixed sign(\Illuminate\Http\File|\Illuminate\Http\UploadedFile $file, string $nik, string $passphrase)
 * @method static mixed signVisibleWithSpesimen(\Illuminate\Http\File|\Illuminate\Http\UploadedFile $file, string $nik, string $passphrase, \Illuminate\Http\File|\Illuminate\Http\UploadedFile $image_ttd, int $page, int $x, int $y, int $width, int $height)
 * @method static mixed signVisibleWithQrCode(\Illuminate\Http\File|\Illuminate\Http\UploadedFile $file, string $nik, string $passphrase, string $link_qrcode, int $page, int $x, int $y, int $width, int $height)
 */

class EsignBsre extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'esign_bsre';
    }
}

# Laravel Storage

A helper package for access ITS file storage API in laravel framework

## Requirements

1. PHP 7.4 or greater
2. Laravel version 8

## Installation

Install using composer:

```shell
composer require dptsi/laravel-esign-bsre
```

## Usage
> @method static mixed cekStatusUser(string $nik)

> @method static mixed sign(\Illuminate\Http\File|\Illuminate\Http\UploadedFile $file, string $nik, string $passphrase)

> @method static mixed signVisibleWithSpesimen(\Illuminate\Http\File|\Illuminate\Http\UploadedFile $file, string $nik, string $passphrase, \Illuminate\Http\File|\Illuminate\Http\UploadedFile $image_ttd, int $page, int $x, int $y, int $width, int $height)

> @method static mixed signVisibleWithQrCode(\Illuminate\Http\File|\Illuminate\Http\UploadedFile $file, string $nik, string $passphrase, string $link_qrcode, int $page, int $x, int $y, int $width, int $height)

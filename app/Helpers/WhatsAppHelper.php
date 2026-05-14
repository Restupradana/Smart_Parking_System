<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class WhatsAppHelper
{
    public static function kirimPesan($nomor, $pesan)
    {
        $url = 'https://api.callmebot.com/whatsapp.php';
        $apikey = 'apikey_anda'; // Ganti dengan API key Anda dari CallMeBot

        $response = Http::get($url, [
            'phone' => $nomor,
            'text' => $pesan,
            'apikey' => $apikey,
        ]);

        return $response->body();
    }
}

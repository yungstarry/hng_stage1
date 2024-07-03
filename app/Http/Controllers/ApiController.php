<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function sayHello(Request $request)
    {
        $visitorName = $request->query("visitor_name", 'Guest');

        $clientIp = $request->ip();

        

        $locationResponse = Http::get("http://ip-api.com/json/{$clientIp}");
        $locationData = $locationResponse->json();

        $city = $locationData["city"] ?? 'Unknown';

        $apiKey = 'ac98f49331bd9d515d897c8b2d0aa566';
        $weatherResponse = Http::get('http://api.openweathermap.org/data/2.5/weather', [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric'
        ]);

        $weatherData = $weatherResponse->json();

        $temperature = $weatherData['main']['temp'] ?? 'Unknown';

        $response = [
            'client_ip' => $clientIp,
            'location' => $city,
            'greeting' => "Hello, $visitorName!, the temperature is $temperature degrees Celsius in $city"
        ];

        return response()->json($response);
    }
}

<?php

namespace App\Services\Report;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DialogFlowService
{
    private string $dialogflowApiUrl = 'https://dialogflow.googleapis.com/v2/projects/YOUR_PROJECT_ID/agent/sessions/SESSION_ID:detectIntent';
    private ?array $serviceAccountCredentials = null;
    private int $tokenExpiryBuffer = 3600; // Tiempo en segundos antes de la expiración para renovar el token


    public function getServiceAccountCredentials(): ?array
    {
        return $this->serviceAccountCredentials;
    }
    public function setServiceAccountCredentials(array $credentials): void
    {
        $this->serviceAccountCredentials = $credentials;
    }

    public function loadCredentials(): ?array
    {
        $filePath = storage_path('app/private/dialog_flow_sergen.json');

        if (!file_exists($filePath)) {
            return ['error' => 'El archivo de credenciales dialog_flow_sergen.json no se encuentra en el directorio storage.'];
        }

        $jsonContent = file_get_contents($filePath);
        $this->setServiceAccountCredentials(json_decode($jsonContent, true));

        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['error' => 'Error al decodificar el archivo JSON de credenciales: ' . json_last_error_msg()];
        }

        return null;
    }

    public function getAccessToken()
    {
        try {
            $now = time();
            $jwt_payload = [
                'iss' => $this->serviceAccountCredentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/cloud-platform',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + $this->tokenExpiryBuffer, // Expira en 1 hora
                'iat' => $now,
            ];

            // Genera el JWT firmado con la clave privada
            $jwt = JWT::encode($jwt_payload, $this->serviceAccountCredentials['private_key'], 'RS256');

            // Intercambia el JWT por un token de acceso
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            $accessToken = $response->json('access_token');
            if (!$accessToken) {
                return ['error' => 'No se pudo obtener el token de acceso: ' . $response->body()];
            }

            return $accessToken;
        } catch (\Exception $e) {
            return ['error' => 'Error de autenticación: ' . $e->getMessage()];
        }
    }

    public function reportRequest($text, $sessionId)
    {

        try {
            // 1. Obtiene un token de acceso JWT para la API de Dialogflow
            $accessToken = $this->getAccessToken();

            if (isset($accessToken['error'])) {
                return $accessToken;
            }

            // 2. Llama a la API de Dialogflow con el token de acceso
            $service = $this->getServiceAccountCredentials();

            $projectId = $service['project_id'] ?? null;
            
            if (!$projectId) {
                return ['error' => 'El project_id no está definido en las credenciales.'];
            }
            
            $apiUrl = str_replace(
                ['YOUR_PROJECT_ID', 'SESSION_ID'],
                [$projectId, $sessionId],
                $this->dialogflowApiUrl
            );

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->post($apiUrl, [
                'queryInput' => [
                    'text' => [
                        'text' => $text,
                        'languageCode' => 'es'
                    ]
                ]
            ]);
            Log::info('Dialogflow response--------------------------------');
            $dialogflowResult = $response->json();
            Log::info($dialogflowResult);
            $parameters = $dialogflowResult['queryResult']['parameters'] ?? [];
            Log::info('Dialogflow parametros--------------------------------');
            Log::info($parameters);
            return $parameters;
        } catch (\Exception $e) {
            return [
                'error' => 'Error al contactar a Dialogflow: ' . $e->getMessage()
            ];
        }
    }
}

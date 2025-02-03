<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class FactusController extends Controller
{
    /**
     * Obtiene un nuevo token de acceso y lo almacena en sesión.
     */
    public function obtenerAccessToken()
    {
        $api_url = env('FACTUS_API_URL') . '/oauth/token';

        $response = Http::asForm()->post($api_url, [
            'grant_type'    => 'password',
            'client_id'     => env('FACTUS_CLIENT_ID'),
            'client_secret' => env('FACTUS_CLIENT_SECRET'),
            'username'      => env('FACTUS_EMAIL'),
            'password'      => env('FACTUS_PASSWORD'),
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // Calcular la hora de expiración (ahora + expires_in)
            $expiresAt = now()->addSeconds($data['expires_in']);

            // Guardar token y su expiración en la sesión
            Session::put('access_token', $data['access_token']);
            Session::put('access_token_expires_at', $expiresAt);

            return $data['access_token'];
        }

        return null;
    }

    /**
     * Obtiene el token de acceso actual o lo refresca si ha expirado.
     */
    public function getAccessToken()
    {
        $expiresAt = Session::get('access_token_expires_at');

        if (!$expiresAt || now()->greaterThan($expiresAt)) {
            return $this->obtenerAccessToken();
        }

        return Session::get('access_token');
    }

    /**
     * Obtiene el listado de facturas desde la API.
     */
    public function obtenerFacturas(Request $request)
    {
        $api_url = env('FACTUS_API_URL') . '/v1/bills';
        $token = $this->getAccessToken(); // Obtiene un token válido

        if (!$token) {
            return response()->json(['error' => 'No se pudo obtener un access_token válido'], 401);
        }

        $page = $request->query('page', 1);

        try {
            $response = Http::withToken($token)->get($api_url, [
                'page' => $page,
                'per_page' => 10
            ]);

            if ($response->successful()) {
                $facturas = $response->json();

                if (isset($facturas['data']['data'])) {
                    return response()->json([
                        'facturas' => $facturas['data']['data'],
                        'current_page' => $facturas['data']['current_page'] ?? $page,
                        'total_pages' => $facturas['data']['last_page'] ?? 1
                    ]);
                }

                return response()->json([
                    'error' => 'Formato de respuesta inesperado',
                    'response_data' => $facturas
                ], 500);
            }

            return response()->json([
                'error' => 'Error en la API de Factus',
                'status_code' => $response->status(),
                'response_body' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Excepción en Laravel',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene el detalle de una factura específica.
     */
    public function verFactura($number)
    {
        $api_url = env('FACTUS_API_URL') . "/v1/bills/show/$number";
        $token = $this->getAccessToken(); // Obtiene un token válido

        if (!$token) {
            return response()->json(['error' => 'No se pudo obtener un access_token válido'], 401);
        }

        try {
            $response = Http::withToken($token)->get($api_url);

            if ($response->successful()) {
                $factura = $response->json();

                if (isset($factura['data']['bill'])) {
                    return view('factura_detalle', ['factura' => $factura['data']]);
                }

                return response()->json([
                    'error' => 'Formato de respuesta inesperado',
                    'response_body' => $factura
                ], 500);
            }

            return response()->json([
                'error' => 'No se pudo obtener la factura',
                'status_code' => $response->status(),
                'response_body' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Excepción en Laravel',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function crearFactura(Request $request)
    {
        $api_url = env('FACTUS_API_URL') . '/v1/bills/validate';
        $token = $this->getAccessToken(); // Obtener el token actualizado

        $data = $request->all(); // Obtener los datos enviados desde la vista

        try {
            $response = Http::withToken($token)->post($api_url, $data);

            if ($response->successful()) {
                return response()->json([
                    'message' => 'Factura creada y validada con éxito',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'error' => 'Error en la API de Factus',
                    'status_code' => $response->status(),
                    'response_body' => $response->json()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Excepción en Laravel',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function obtenerMunicipios()
    {
        $api_url = env('FACTUS_API_URL') . "/v1/municipalities?name=";
        $token = $this->getAccessToken(); // Asegura que el token está actualizado

        if (!$token) {
            return response()->json(['error' => 'No se pudo obtener un access_token válido'], 401);
        }

        try {
            $response = Http::withToken($token)->get($api_url);

            if ($response->successful()) {
                $municipios = $response->json();
                
                if (isset($municipios['data'])) {
                    return response()->json($municipios['data']); // Solo envía los municipios
                }
            }

            return response()->json([
                'error' => 'Error obteniendo municipios',
                'status_code' => $response->status(),
                'response_body' => $response->json()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Excepción en Laravel',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function obtenerRangosNumeracion()
    {
        $api_url = env('FACTUS_API_URL') . "/v1/numbering-ranges";
        $token = $this->getAccessToken();

        if (!$token) {
            return response()->json(['error' => 'No se pudo obtener un access_token válido'], 401);
        }

        try {
            $response = Http::withToken($token)->get($api_url);

            if ($response->successful()) {
                return response()->json($response->json()['data']);
            } else {
                return response()->json(['error' => 'No se pudieron obtener los rangos de numeración'], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error en la petición de rangos de numeración',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function obtenerUnidadesMedida()
    {
        $api_url = env('FACTUS_API_URL') . "/v1/measurement-units?name=";
        $token = $this->getAccessToken(); // Obtener token válido
    
        try {
            $response = Http::withToken($token)->get($api_url);
    
            if ($response->successful()) {
                return response()->json($response->json()['data']);
            } else {
                return response()->json(['error' => 'No se pudieron obtener las unidades de medida'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la petición de unidades de medida', 'message' => $e->getMessage()], 500);
        }
    }

    public function obtenerTributosProductos()
    {
        $api_url = env('FACTUS_API_URL') . "/v1/tributes/products?name=";
        $token = $this->getAccessToken(); // Obtener token válido

        try {
            $response = Http::withToken($token)->get($api_url);

            if ($response->successful()) {
                return response()->json($response->json()['data']);
            } else {
                return response()->json(['error' => 'No se pudieron obtener los tributos de productos'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la petición de tributos de productos', 'message' => $e->getMessage()], 500);
        }
    }

    public function generarFactura(Request $request)
    {
        $api_url = env('FACTUS_API_URL') . '/v1/bills/validate';
        $token = $this->getAccessToken(); // Obtener el token actualizado

        if (!$token) {
            return response()->json(['error' => 'No se pudo obtener un access_token válido'], 401);
        }

        $data = $request->all(); // Obtener los datos enviados desde la vista

        try {
            $response = Http::withToken($token)->post($api_url, $data);
            $responseData = $response->json(); // Obtener la respuesta en JSON

            if ($response->successful() && isset($responseData['data'])) {
                return response()->json([
                    'message' => 'Factura creada y validada con éxito',
                    'data' => $responseData['data']['bill'],  // Extrae directamente los datos de la factura
                    'company' => $responseData['data']['company'] ?? null, // Extrae datos de la empresa
                ]);
            } else {
                return response()->json([
                    'error' => 'Error en la API de Factus',
                    'status_code' => $response->status(),
                    'response_body' => $responseData
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Excepción en Laravel',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    
}

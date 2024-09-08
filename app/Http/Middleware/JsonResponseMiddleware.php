<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Vérifie si la réponse est une instance de JsonResponse
        if ($response instanceof JsonResponse) {
            return $this->formatJsonResponse($response);
        }

        // Traitement des réponses vides ou autres types de réponses
        if ($response->getContent() === '' && in_array($response->getStatusCode(), [204])) {
            return $this->formatEmptyResponse($response);
        }

        // Vérifie si la réponse est un objet ou une collection (succès)
        if (is_object($response)) {
            return $this->formatObjectResponse($response);
        }

        // Si la réponse est null (échec)
        if (is_null($response)) {
            return $this->formatNullResponse();
        }

        return $response;
    }

    /**
     * Format JSON response to include status and message.
     *
     * @param  \Illuminate\Http\JsonResponse  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function formatJsonResponse(JsonResponse $response): Response
    {
        $originalData = $response->getData(true);
        $statusCode = $response->getStatusCode();

        $status = $this->getStatusFromCode($statusCode);
        $message = $originalData['message'] ?? $this->getMessageFromCode($statusCode);

        $standardResponse = [
            'status' => $status,
            'data' => $originalData['data'] ?? null,
            'message' => $message,
        ];

        return response()->json($standardResponse, $statusCode);
    }

    /**
     * Format empty response to include a success message.
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function formatEmptyResponse(Response $response): Response
    {
        $statusCode = $response->getStatusCode();
        $status = $this->getStatusFromCode($statusCode);
        $message = $this->getMessageFromCode($statusCode);

        // Ajoute un message spécifique pour les réponses vides (204 No Content)
        if ($statusCode === 204) {
            $message = 'Opération réussie.';
        }

        $standardResponse = [
            'status' => $status,
            'data' => null,
            'message' => $message,
        ];

        return response()->json($standardResponse, $statusCode);
    }

    /**
     * Format the response when an object is returned (success scenario).
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function formatObjectResponse($response): Response
    {
        $standardResponse = [
            'status' => 'success',
            'data' => $response,
            'message' => 'Opération réussie.'
        ];

        return response()->json($standardResponse, 200);
    }

    /**
     * Format the response when null is returned (failure scenario).
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function formatNullResponse(): Response
    {
        $standardResponse = [
            'status' => 'error',
            'data' => null,
            'message' => 'Échec de l\'opération.'
        ];

        return response()->json($standardResponse, 400);
    }

    /**
     * Get the status based on the HTTP status code.
     *
     * @param  int  $statusCode
     * @return string
     */
    protected function getStatusFromCode(int $statusCode): string
    {
        return $statusCode >= 200 && $statusCode < 300 ? 'success' : 'error';
    }

    /**
     * Get the default message based on the HTTP status code.
     *
     * @param  int  $statusCode
     * @return string
     */
    protected function getMessageFromCode(int $statusCode): string
    {
        $messages = [
            200 => 'Opération réussie.',
            201 => 'Création réussie.',
            204 => 'Aucune donnée à retourner.',
            400 => 'Requête incorrecte.',
            401 => 'Non autorisé.',
            403 => 'Accès interdit.',
            404 => 'Ressource non trouvée.',
            500 => 'Erreur interne du serveur.',
            503 => 'Service temporairement indisponible.',
        ];

        return $messages[$statusCode] ?? 'Une erreur s\'est produite.';
    }
}

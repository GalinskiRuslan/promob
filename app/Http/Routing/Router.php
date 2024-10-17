<?php

namespace App\Http\Routing;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use stdClass;
use Illuminate\Http\Response;
use JsonSerializable;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Illuminate\Support\Stringable;
use ArrayObject;
use Illuminate\Database\Eloquent\Model;





class Router extends \Illuminate\Routing\Router
{
    public static function toResponse($request, $response)
    {
        if ($response instanceof Responsable) {
            $response = $response->toResponse($request);
        }

        if ($response instanceof PsrResponseInterface) {
            $response = (new HttpFoundationFactory)->createResponse($response);
        } elseif ($response instanceof Model && $response->wasRecentlyCreated) {
            $response = new JsonResponse($response, 201);
        } elseif ($response instanceof Stringable) {
            $response = new Response($response->__toString(), 200, ['Content-Type' => 'text/html'], [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } elseif (
            ! $response instanceof SymfonyResponse &&
            ($response instanceof Arrayable ||
                $response instanceof Jsonable ||
                $response instanceof ArrayObject ||
                $response instanceof JsonSerializable ||
                $response instanceof stdClass ||
                is_array($response))
        ) {
            $response = new JsonResponse($response);
        } elseif (! $response instanceof SymfonyResponse) {
            $response = new Response($response, 200, ['Content-Type' => 'text/html'], [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }

        if ($response->getStatusCode() === Response::HTTP_NOT_MODIFIED) {
            $response->setNotModified();
        }

        return $response->prepare($request);
    }
}

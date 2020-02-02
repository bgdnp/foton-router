<?php

namespace Bgdnp\Foton\Http;

class RouteResolver
{
    public function resolve(string $requestRoute, array $routes)
    {
        $splits = explode('/', trim($requestRoute, ' /'));

        foreach ($routes as $path => $route) {
            if ($path === '/' . trim($requestRoute, ' /')) {
                return $route;
            }

            $routeSplits = explode('/', trim($path, ' /'));

            if (count($splits) !== count($routeSplits)) {
                continue;
            }

            $match = false;

            foreach ($routeSplits as $index => $split) {
                $isDynamic = preg_match('/\[([^\]]+)\]/', $split, $matches);

                if ($isDynamic) {
                    $route->setParameter($matches[1], $splits[$index]);
                }

                if (!$isDynamic && $split !== $splits[$index]) {
                    $match = false;
                    break;
                }

                $match = true;
            }

            if ($match) {
                return $route;
            }
        }
    }
}

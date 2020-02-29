<?php

namespace Bgdnp\FotonRouter;

class RouteResolver
{
    public function resolve(string $requestPath, array $routes)
    {
        foreach ($routes as $definedPath => $route) {
            if ($matchedRoute = $this->testRoute($requestPath, $definedPath, $route)) {
                return $matchedRoute;
            }
        }
    }

    protected function testRoute($requestPath, $definedPath, $route)
    {
        if ($this->isExactMatch($definedPath, $requestPath)) {
            return $route;
        }

        $requestPathSegments = explode('/', trim($requestPath, ' /'));
        $definedPathSegments = explode('/', trim($definedPath, ' /'));

        if (!$this->isSegmentsLengthEqual($definedPathSegments, $requestPathSegments)) {
            return null;
        }

        return $this->testSegments($requestPathSegments, $definedPathSegments, $route);
    }

    protected function testSegments(array $requestPathSegments, array $definedPathSegments, $route)
    {
        foreach ($definedPathSegments as $index => $definedPathSegment) {
            if (!$this->testSegment($requestPathSegments[$index], $definedPathSegment, $route)) {
                return null;
            }
        }

        return $route;
    }

    protected function testSegment($requestPathSegment, $definedPathSegment, $route)
    {
        if ($this->isDynamicSegment($definedPathSegment)) {
            $route->setParameter($definedPathSegment, $requestPathSegment);
        }

        if (!$this->isSegmentValid($definedPathSegment, $requestPathSegment)) {
            return false;
        }

        return true;
    }

    protected function isExactMatch(string $definedPath, string $requestPath): bool
    {
        return $definedPath === '/' . trim($requestPath, ' /');
    }

    protected function isSegmentsLengthEqual(array $definedPathSegments, array $requestPathSegments): bool
    {
        return count($requestPathSegments) === count($definedPathSegments);
    }

    protected function isSegmentValid(string $definedPathSegment, string $requestPathSegment)
    {
        return $this->isDynamicSegment($definedPathSegment) || $definedPathSegment === $requestPathSegment;
    }

    protected function isDynamicSegment(string $definedPathSegment): bool
    {
        return (bool) preg_match('/\[([^\]]+)\]/', $definedPathSegment);
    }
}

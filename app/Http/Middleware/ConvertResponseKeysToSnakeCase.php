<?php

namespace App\Http\Middleware;

use App\Helper\Json;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ConvertResponseKeysToSnakeCase
{
    public function __construct(
        private Json $jsonHelper,
    ){
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $content = $response->getContent();

        if($this->jsonHelper->isJson($content) === false) {
            return $response;
        }

        $decoded = json_decode($content, true, 512, JSON_THROW_ON_ERROR|JSON_PRETTY_PRINT);

        $convertedContent = $this->convertKeysToSnakeCase($decoded);

        $response->setContent(json_encode($convertedContent, JSON_THROW_ON_ERROR|JSON_PRETTY_PRINT));

        return $response;
    }

    private function convertKeysToSnakeCase(array $toConvert): array
    {
        $converted = [];
        foreach ($toConvert as $key => $value) {
            if(is_array($value)) {
                $converted[Str::snake($key)] = $this->convertKeysToSnakeCase($value);

                continue;
            }

            $converted[Str::snake($key)] = $value;
        }

        return $converted;
    }
}

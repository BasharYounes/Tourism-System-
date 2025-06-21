<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Symfony\Component\HttpFoundation\Response;

class TranslationMiddleware
{
    public $language,$translator;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next) {
        $language = $request->header('X-Language', 'en');
        $translator = new GoogleTranslate();
        $translator->setTarget($language);
        $request->translator = $translator; 
        return $next($request);
    }
}

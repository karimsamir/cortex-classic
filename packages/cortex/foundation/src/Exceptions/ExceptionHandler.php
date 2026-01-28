<?php

namespace Cortex\Foundation\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as BaseHandler;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class ExceptionHandler extends BaseHandler
{
    protected $dontReport = [];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
        });
    }

    /**
     * Report or log an exception.
     */
    public function report(Throwable $e)
    {
        // Suppress "Target class [composer] does not exist" error reporting
        // This is a benign error that occurs during artisan command initialization
        if ($this->isComposerBindingError($e)) {
            return;
        }

        parent::report($e);
    }

    /**
     * Render an exception into a response.
     */
    public function render($request, Throwable $e)
    {
        // Suppress composer binding errors in console output
        if ($this->isComposerBindingError($e)) {
            // Return empty output to suppress the error display
            if ($request instanceof OutputInterface) {
                return;
            }
        }

        return parent::render($request, $e);
    }

    /**
     * Check if the exception is a composer binding error.
     */
    protected function isComposerBindingError(Throwable $e): bool
    {
        $message = $e->getMessage();
        return strpos($message, 'Target class [composer]') !== false ||
               strpos($message, 'Class "composer" does not exist') !== false;
    }
}

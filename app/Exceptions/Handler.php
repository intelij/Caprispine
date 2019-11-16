<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if($this->isHttpException($e))
        {
            if($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException){
                return redirect('/');
            }
            switch ($e->getStatusCode()) 
                {
                    
                // not authorized
                case '403':
                return redirect('/');
                break;

                // internal error
                case '503':
                return redirect('/');
                break;  

                // not found
                case '404':
                return redirect('/');
                break;

                // internal error
                case '500':
                return redirect('/');
                break;

                default:
                    return $this->renderHttpException($e);
                break;
            }
        }
        else
        {
            return parent::render($request, $e);
        } 
    }
}

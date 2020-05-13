<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Mail;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use App\Mail\DefaultEmail;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception)) {
            $this->sendEmail($exception); // sends an email
        }
        //parent::report($exception);

        //error log simples
        $linha = (isset($exception->getTrace()[0]['line']) ? $exception->getTrace()[0]['line'] : '');
        $file = (isset($exception->getTrace()[0]['file']) ? $exception->getTrace()[0]['file'] : '');
        Log::error('['.$exception->getCode().'] "'.$exception->getMessage().'" on line '.$linha.' of file '.$file);

        //error log completo com stack trace
        //Log::channel('full')->error($exception->getTraceAsString());
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * Sends an email to the developer about the exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function sendEmail(Exception $exception)
    {
        //envia email somente se não estiver no ambiente de desenvolvimento (APP_DEBUG = false)

        $debugMode = env('APP_DEBUG',false);
        if (!$debugMode) {
            try {
                $e = FlattenException::create($exception);
                $handler = new SymfonyExceptionHandler();
                $html = $handler->getHtml($e);
                Mail::to(env('MAIL_ERROR', 'webmaster@objetivars.com.br'))->send(new DefaultEmail($html));
            } catch (Exception $ex) {
                dd($ex);
            }
        }
    }
}

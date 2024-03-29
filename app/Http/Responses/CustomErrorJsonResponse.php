<?php

namespace App\Http\Responses;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CustomErrorJsonResponse extends CustomJsonResponse
{
    /**
     * @param  string $error
     * @param  int $errorCode
     */
    public function __construct(string $error, int $errorCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {

        if ($errorCode < 400) {
            throw new Exception('The passed status code (' . $errorCode . ') is not an error.');
        }

        parent::__construct($error, $errorCode);

        $this->setResponseContent([
            'error_message' => $this->getError(),
            'error_code' => $this->getErrorCode()
        ]);

        Log::error(json_encode([$error, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10)]));
    }

    /**
     * Returns the error message.
     *
     * @return string
     */
    public function getError(): string
    {
        return $this->getData();
    }

    /**
     * Returns the error status code.
     *
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->getStatusCode();
    }
}

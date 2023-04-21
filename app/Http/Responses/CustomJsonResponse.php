<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CustomJsonResponse
{
    /**
     * @var JsonResponse $response an immutable instance of the JSON response
     */
    protected JsonResponse $response;
    
    /**
     * @param  mixed $data
     * @param  int $statusCode
     */
    public function __construct(
        protected mixed $data, 
        protected int $statusCode = Response::HTTP_OK
    ) {
        $this->setResponseContent([
            'data' => $this->data,
            'status_code' => $statusCode
        ]);
    }
    
    /**
     * Returns the json response
     *
     * @return JsonResponse
     */
    public function get(): JsonResponse
    {
        return $this->response;
    }
    
    /**
     * Returns the passed data.
     *
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }
    
    /**
     * Returns the error status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    
    /**
     * Set the immutable response using the passed content
     *
     * @param  array $content
     * 
     * @return void
     */
    protected function setResponseContent(array $content): void
    {
        $this->response = response()->json($content, $this->getStatusCode());
    }
}
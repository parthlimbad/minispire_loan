<?php 

namespace App\Traits;

use Response;

trait CommonTrait
{

    /**
     * Returns error json response
     *
     * @param string $error
     * @param integer $code
     * @return string
     * @author Parth L.
     */
    public function error($error, $code = 404)
    {
        return Response::json([
            'success' => false,
            'message' => $error,
        ], $code);
    }
    
    /**
     * Returns success json response
     *
     * @param string $message
     * @param mixed $data
     * @param integer $code
     * @return string
     * @author Parth L.
     */
    public function success($message, $data = NULL, $code = 200)
    {
        return Response::json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
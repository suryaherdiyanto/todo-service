<?php

use Validator;

function validateRequest(&$requestData, $rules = [])
{
    $validator = Validator::make($requestData, $rules);
    
    if ($validator->fails()) {
        throw new \Dingo\Api\Exception\StoreResourceFailedException("Error sending request", $validator->errors());
    }
    
}
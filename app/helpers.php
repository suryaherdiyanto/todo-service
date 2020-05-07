<?php

use Validator;

function validateRequest(&$requestData, $rules = [])
{
    $validator = Validator::make($requestData, $rules);
    
    if ($validator->fails()) {
        throw \Dingo\Api\Exception\StoreResourceFailedException("Validation error", $validator->errors());
    }
    
}
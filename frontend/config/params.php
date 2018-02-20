<?php

$age_range = [
    'age.min' => '18 year',
    'age.max' => '100 year'
];

$sms_range = [
    'code.min' => 1000,
    'code.max' => 9999
];

return array_merge(
    $age_range,
    $sms_range
);
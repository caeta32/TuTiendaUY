<?php
require 'vendor/autoload.php';
use Aws\S3\S3Client;
$bucketName = 'tutiendaimagenes';
 
$client = new S3Client([
    'version' => 'latest',
    'region' => 'us-east-2',
    'credentials' => [
        'key'    => 'AKIATYWMB2PSQ4MPMOPD',
        'secret' => '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'
    ]
]);
 
try {
    $result = $client->createBucket([
        'Bucket' => $bucketName, // REQUIRED
        'ACL'    => 'public-read',
    ]);
    echo "Bucket created successfully.";
} catch (Aws\S3\Exception\S3Exception $e) {
    // output error message if fails
    echo $e->getMessage();
}
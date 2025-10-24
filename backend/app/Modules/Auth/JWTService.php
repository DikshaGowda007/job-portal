<?php
namespace App\Modules\Auth;

class JwtService
{

    private static function getSecretKey(): string
    {
        $key = env('JWT_SECRET');

        if (empty($key)) {
            throw new \Exception('JWT secret key not found in environment variables.');
        }

        return $key;
    }
    private static string $algo = 'HS256';

    public static function generateToken(array $payload, int $expiryInSeconds = 3600): string
    {
        $issuedAt = time();
        $expireAt = $issuedAt + $expiryInSeconds;

        $payload['iat'] = $issuedAt;
        $payload['exp'] = $expireAt;

        $base64Header = base64_encode(json_encode(['alg' => self::$algo, 'typ' => 'JWT']));
        $base64Payload = base64_encode(json_encode($payload));

        $secretKey = self::getSecretKey();
        $signature = hash_hmac('sha256', "$base64Header.$base64Payload", $secretKey, true);
        $base64Signature = base64_encode($signature);

        return "$base64Header.$base64Payload.$base64Signature";
    }

    public static function decodeToken(string $token): ?array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return null;
        }

        [$header, $payload, $signature] = $parts;
        $secretKey = self::getSecretKey();

        $expectedSignature = base64_encode(hash_hmac('sha256', "$header.$payload", $secretKey, true));

        if (!hash_equals($expectedSignature, $signature)) {
            return null;
        }

        $decodedPayload = json_decode(base64_decode($payload), true);
        // if (isset($decodedPayload['exp']) && time() > $decodedPayload['exp']) {
        //     return null; // expired
        // }

        return $decodedPayload;
    }
}
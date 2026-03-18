<?php

namespace Twix\Helper;

class SignatureHelper
{
    /**
     * Generate signature for payment link.
     *
     * @param string $secretKey Secret key from button
     * @param float $amount Amount in fiat
     * @param string|null $orderId Optional order ID
     * @param string|null $userName Optional user name
     * @param string|null $returnSuccessUrl Optional success URL
     * @param string|null $returnFailUrl Optional fail URL
     * @return string Base64 encoded signature
     * @throws \Exception
     */
    public static function generatePaymentLinkSignature(
        string $secretKey,
        float $amount,
        ?string $orderId = null,
        ?string $userName = null,
        ?string $returnSuccessUrl = null,
        ?string $returnFailUrl = null
    ): string {
        $data = 'order_id=' . ($orderId ?? '')
            . '&user_name=' . ($userName ?? '')
            . '&return_success_url=' . ($returnSuccessUrl ?? '')
            . '&return_fail_url=' . ($returnFailUrl ?? '')
            . '&amount=' . $amount;

        $binaryKey = hash('sha256', $secretKey, true);
        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $binaryKey, OPENSSL_RAW_DATA, $iv);

        if ($encrypted === false) {
            throw new \Exception('Encryption failed');
        }

        return base64_encode($iv . $encrypted);
    }

    /**
     * Verify webhook signature.
     *
     * @param array $payload Full webhook payload (including signature)
     * @param string $secretKey Secret key
     * @return bool
     */
    public static function verifyWebhookSignature(array $payload, string $secretKey): bool
    {
        if (!isset($payload['signature'], $payload['type'], $payload['data'])) {
            return false;
        }

        $signature = $payload['signature'];
        $data = [
            'type' => $payload['type'],
            'data' => $payload['data'],
        ];

        $sorted = self::sortKeysRecursive($data);
        $json = json_encode($sorted, JSON_THROW_ON_ERROR);
        $jsonNoSpaces = str_replace(' ', '', $json);
        $jsonPrepared = trim($jsonNoSpaces);
        $expected = sha1($jsonPrepared . $secretKey);

        return hash_equals($expected, $signature);
    }

    private static function sortKeysRecursive(array $array): array
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::sortKeysRecursive($value);
            }
        }
        ksort($array);
        return $array;
    }
}
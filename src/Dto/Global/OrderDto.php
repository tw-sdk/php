<?php

namespace Twix\Dto\Global;

/**
 * Class OrderDto
 *
 */
class OrderDto
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var int|null
     */
    private $orderId;

    /**
     * @var string|null
     */
    private $customId;

    /**
     * @var BankDto|null
     */
    private $bank;

    /**
     * @var int|null
     */
    private $providerId;

    /**
     * @var string|null
     */
    private $paymentSystem;

    /**
     * @var string|null
     */
    private $rateProvider;

    /**
     * @var bool|null
     */
    private $isCis;

    /**
     * @var string|null
     */
    private $fiatAmount;

    /**
     * @var string|null
     */
    private $fiatCurrency;

    /**
     * @var float|null
     */
    private $cryptoAmount;

    /**
     * @var string|int|null
     */
    private $chatId;

    /**
     * @var string|null
     */
    private $cryptoCurrency;

    /**
     * @var string|null
     */
    private $cryptoAmountProvider;

    /**
     * @var int|string|null
     */
    private $processorDocument;

    /**
     * @var string|null
     */
    private $requisites;

    /**
     * @var string|null
     */
    private $requisitesFullname;

    /**
     * @var string|null
     */
    private $mobileOperator;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string|null
     */
    private $receipt;

    /**
     * @var string|null
     */
    private $receiptUrl;

    /**
     * @var string|null
     */
    private $videoLink;

    /**
     * @var string|null
     */
    private $automaticResolutionAt;

    /**
     * @var string|null
     */
    private $deepLink;

    /**
     * @var int|null
     */
    private $commission;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @var string
     */
    private $updatedAt;

    /**
     * Extract order data from various API response formats.
     *
     * @param array $data
     * @return array
     */
    private static function extractOrderData(array $data): array
    {
        // Прямое возвращение, если это уже сам ордер
        if (isset($data['id']) && isset($data['status'])) {
            return $data;
        }

        // Извлечение из различных форматов ответов API
        $possiblePaths = [
            'data.order',
            'data',
            'order',
            'orders.0',
        ];

        foreach ($possiblePaths as $path) {
            $keys = explode('.', $path);
            $current = $data;

            foreach ($keys as $key) {
                if (!isset($current[$key])) {
                    $current = null;
                    break;
                }
                $current = $current[$key];
            }

            if (is_array($current) && isset($current['id']) && isset($current['status'])) {
                return $current;
            }
        }

        // Если это массив ордеров, возвращаем первый
        if (isset($data[0]) && is_array($data[0]) && isset($data[0]['id'])) {
            return $data[0];
        }

        // Если ничего не нашли, возвращаем исходные данные
        // (может быть сам ордер или пустой массив)
        return $data;
    }


    /**
     * Extract multiple orders from various API response formats.
     *
     * @param array $data
     * @return array
     */
    public static function extractOrdersArray(array $data): array
    {
        if (isset($data[0]) && is_array($data[0]) && isset($data[0]['id'])) {
            return $data;
        }

        $possiblePaths = [
            'data.data',
            'data.orders',
            'data',
            'orders',
        ];

        foreach ($possiblePaths as $path) {
            $keys = explode('.', $path);
            $current = $data;

            foreach ($keys as $key) {
                if (!isset($current[$key])) {
                    $current = null;
                    break;
                }
                $current = $current[$key];
            }

            if (is_array($current)) {
                if (isset($current[0]) && isset($current[0]['id'])) {
                    return $current;
                }
                if (isset($current['id']) && isset($current['status'])) {
                    return [$current];
                }
            }
        }

        return [];
    }


    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $orderData = self::extractOrderData($data);
        $dto = new self();

        $dto->id = $orderData['id'] ?? '';
        $dto->orderId = isset($orderData['order_id']) ? (int) $orderData['order_id'] : null;
        $dto->customId = $orderData['custom_id'] ?? null;

        if (isset($orderData['bank']) && is_array($orderData['bank'])) {
            $dto->bank = BankDto::fromArray($orderData['bank']);
        }

        $dto->providerId = isset($orderData['provider_id']) ? (int) $orderData['provider_id'] : null;
        $dto->paymentSystem = $orderData['payment_system'] ?? null;
        $dto->rateProvider = $orderData['rate_provider'] ?? null;
        $dto->isCis = $orderData['is_cis'] ?? null;
        $dto->fiatAmount = $orderData['fiat_amount'] ?? null;
        $dto->fiatCurrency = $orderData['fiat_currency'] ?? null;
        $dto->cryptoAmount = isset($orderData['crypto_amount']) ? (float) $orderData['crypto_amount'] : null;
        $dto->chatId = $orderData['chat_id'] ?? null;
        $dto->cryptoCurrency = $orderData['crypto_currency'] ?? null;
        $dto->cryptoAmountProvider = $orderData['crypto_amount_provider'] ?? null;
        $dto->processorDocument = $orderData['processor_document'] ?? null;
        $dto->requisites = $orderData['requisites'] ?? null;
        $dto->requisitesFullname = $orderData['requisites_fullname'] ?? null;
        $dto->mobileOperator = $orderData['mobile_operator'] ?? null;
        $dto->status = $orderData['status'] ?? '';
        $dto->receipt = $orderData['receipt'] ?? null;
        $dto->receiptUrl = $orderData['receipt_url'] ?? null;
        $dto->videoLink = $orderData['video_link'] ?? null;
        $dto->automaticResolutionAt = $orderData['automatic_resolution_at'] ?? null;
        $dto->deepLink = $orderData['deep_link'] ?? null;
        $dto->commission = isset($orderData['commission']) ? (int) $orderData['commission'] : null;
        $dto->createdAt = $orderData['created_at'] ?? '';
        $dto->updatedAt = $orderData['updated_at'] ?? '';

        return $dto;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'order_id' => $this->orderId,
            'custom_id' => $this->customId,
            'provider_id' => $this->providerId,
            'payment_system' => $this->paymentSystem,
            'rate_provider' => $this->rateProvider,
            'is_cis' => $this->isCis,
            'fiat_amount' => $this->fiatAmount,
            'fiat_currency' => $this->fiatCurrency,
            'crypto_amount' => $this->cryptoAmount,
            'chat_id' => $this->chatId,
            'crypto_currency' => $this->cryptoCurrency,
            'crypto_amount_provider' => $this->cryptoAmountProvider,
            'processor_document' => $this->processorDocument,
            'requisites' => $this->requisites,
            'requisites_fullname' => $this->requisitesFullname,
            'mobile_operator' => $this->mobileOperator,
            'status' => $this->status,
            'receipt' => $this->receipt,
            'receipt_url' => $this->receiptUrl,
            'video_link' => $this->videoLink,
            'automatic_resolution_at' => $this->automaticResolutionAt,
            'deep_link' => $this->deepLink,
            'commission' => $this->commission,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];

        if ($this->bank !== null) {
            $data['bank'] = $this->bank->toArray();
        }

        return array_filter($data, function ($value) {
            return $value !== null;
        });
    }

    // Геттеры
    public function getId(): string { return $this->id; }
    public function getOrderId(): ?int { return $this->orderId; }
    public function getCustomId(): ?string { return $this->customId; }
    public function getBank(): ?BankDto { return $this->bank; }
    public function getProviderId(): ?int { return $this->providerId; }
    public function getPaymentSystem(): ?string { return $this->paymentSystem; }
    public function getRateProvider(): ?string { return $this->rateProvider; }
    public function getIsCis(): ?bool { return $this->isCis; }
    public function getFiatAmount(): ?string { return $this->fiatAmount; }
    public function getFiatCurrency(): ?string { return $this->fiatCurrency; }
    public function getCryptoAmount(): ?float { return $this->cryptoAmount; }
    public function getChatId() { return $this->chatId; }
    public function getCryptoCurrency(): ?string { return $this->cryptoCurrency; }
    public function getCryptoAmountProvider(): ?string { return $this->cryptoAmountProvider; }
    public function getProcessorDocument() { return $this->processorDocument; }
    public function getRequisites(): ?string { return $this->requisites; }
    public function getRequisitesFullname(): ?string { return $this->requisitesFullname; }
    public function getMobileOperator(): ?string { return $this->mobileOperator; }
    public function getStatus(): string { return $this->status; }
    public function getReceipt(): ?string { return $this->receipt; }
    public function getReceiptUrl(): ?string { return $this->receiptUrl; }
    public function getVideoLink(): ?string { return $this->videoLink; }
    public function getAutomaticResolutionAt(): ?string { return $this->automaticResolutionAt; }
    public function getDeepLink(): ?string { return $this->deepLink; }
    public function getCommission(): ?int { return $this->commission; }
    public function getCreatedAt(): string { return $this->createdAt; }
    public function getUpdatedAt(): string { return $this->updatedAt; }
}

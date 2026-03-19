<?php

namespace Twix\Enum;

/**
 * Class CryptoCurrency
 *
 * @method static self BTC()
 * @method static self USDT()
 * @method static self ETH()
 * @method static self LTC()
 */
class CryptoCurrency extends BaseEnum
{
    public const BTC = 'BTC';
    public const USDT = 'USDT';
    public const ETH = 'ETH';
    public const LTC = 'LTC';

    public static function getValidValues(): array
    {
        return [
            self::BTC,
            self::USDT,
            self::ETH,
            self::LTC,
        ];
    }
}
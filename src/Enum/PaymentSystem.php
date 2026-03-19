<?php

namespace Twix\Enum;

/**
 * Class PaymentSystem
 * 
 * @method static self CCARD()
 * @method static self SBP()
 * @method static self SIM()
 * @method static self QR_NCPK()
 */
class PaymentSystem extends BaseEnum
{
    public const CCARD = 'ccard';
    public const SBP = 'sbp';
    public const SIM = 'sim';
    public const QR_NCPK = 'qr_ncpk';

    public static function getValidValues(): array
    {
        return [
            self::CCARD,
            self::SBP,
            self::SIM,
            self::QR_NCPK,
        ];
    }
}
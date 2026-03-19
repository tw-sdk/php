<?php

namespace Twix\Enum;

/**
 * Class OrderStatusFilter
 *
 * @method static self ACTIVE()
 * @method static self DISPUTE()
 * @method static self FINISHED()
 * @method static self CANCELED()
 * @method static self CANCELED_CONFIRMED()
 */
class OrderStatusFilter extends BaseEnum
{
    public const ACTIVE = 'active';
    public const DISPUTE = 'dispute';
    public const FINISHED = 'finished';
    public const CANCELED = 'canceled';
    public const CANCELED_CONFIRMED = 'canceled_confirmed';

    public static function getValidValues(): array
    {
        return [
            self::ACTIVE,
            self::DISPUTE,
            self::FINISHED,
            self::CANCELED,
            self::CANCELED_CONFIRMED,
        ];
    }
}

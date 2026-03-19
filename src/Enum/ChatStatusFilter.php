<?php

namespace Twix\Enum;

/**
 * Class ChatStatus
 *
 * @method static self CANCELED()
 * @method static self INPROCESS()
 * @method static self DISPUTE()
 * @method static self DONE()
 */
class ChatStatusFilter extends BaseEnum
{
    public const CANCELED = 'canceled';
    public const INPROCESS = 'inprocess';
    public const DISPUTE = 'dispute';
    public const DONE = 'done';

    public static function getValidValues(): array
    {
        return [
            self::CANCELED,
            self::INPROCESS,
            self::DISPUTE,
            self::DONE,
        ];
    }
}

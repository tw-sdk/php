<?php

namespace Twix\Enum;

/**
 * Class OrderStatus
 *
 * @method static self NEW()
 * @method static self REQUISITE_ADDED()
 * @method static self MARKED_PAID()
 * @method static self TIMER_PAID()
 * @method static self WAITING_FOR_PROOFS()
 * @method static self PARTIALLY_COMPLETED()
 * @method static self FINISHED()
 * @method static self CANCELED()
 * @method static self CANCELED_CONFIRMED()
 * @method static self DISPUTE()
 */
class OrderStatus extends BaseEnum
{
    public const NEW = 'new';
    public const REQUISITE_ADDED = 'requisite_added';
    public const MARKED_PAID = 'marked_paid';
    public const TIMER_PAID = 'timer_paid';
    public const WAITING_FOR_PROOFS = 'waiting_for_proofs';
    public const PARTIALLY_COMPLETED = 'partially_completed';
    public const FINISHED = 'finished';
    public const CANCELED = 'canceled';
    public const CANCELED_CONFIRMED = 'canceled_confirmed';
    public const DISPUTE = 'dispute';

    public static function getValidValues(): array
    {
        return [
            self::NEW,
            self::REQUISITE_ADDED,
            self::MARKED_PAID,
            self::TIMER_PAID,
            self::WAITING_FOR_PROOFS,
            self::PARTIALLY_COMPLETED,
            self::FINISHED,
            self::CANCELED,
            self::CANCELED_CONFIRMED,
            self::DISPUTE,
        ];
    }
}
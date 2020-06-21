<?php

/*
 * This file is part of Laravel Love.
 *
 * (c) Anton Komarev <anton@komarev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Cog\Laravel\Love\Reactant\ReactionCounter\Models;

use Cog\Contracts\Love\Reactant\Models\Reactant as ReactantContract;
use Cog\Contracts\Love\Reactant\ReactionCounter\Models\ReactionCounter as ReactionCounterContract;
use Cog\Contracts\Love\ReactionType\Models\ReactionType as ReactionTypeContract;
use Cog\Laravel\Love\Reactant\Models\Reactant;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Cog\Laravel\Love\Support\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ReactionCounter extends Model implements
    ReactionCounterContract
{
    public const COUNT_DEFAULT = 0;

    public const WEIGHT_DEFAULT = 0.0;

    protected $table = 'love_reactant_reaction_counters';

    /**
     * @var array<int|float>
     */
    protected $attributes = [
        'count' => self::COUNT_DEFAULT,
        'weight' => self::WEIGHT_DEFAULT,
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'reaction_type_id',
        'count',
        'weight',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'count' => 'integer',
        'weight' => 'float',
    ];

    public function reactant(): BelongsTo
    {
        return $this->belongsTo(Reactant::class, 'reactant_id');
    }

    public function reactionType(): BelongsTo
    {
        return $this->belongsTo(ReactionType::class, 'reaction_type_id');
    }

    public function getReactant(): ReactantContract
    {
        return $this->getAttribute('reactant');
    }

    public function getReactionType(): ReactionTypeContract
    {
        return $this->getAttribute('reactionType');
    }

    public function isReactionOfType(
        ReactionTypeContract $reactionType
    ): bool {
        return $this->getReactionType()->isEqualTo($reactionType);
    }

    public function isNotReactionOfType(
        ReactionTypeContract $reactionType
    ): bool {
        return !$this->isReactionOfType($reactionType);
    }

    public function getCount(): int
    {
        return $this->getAttributeValue('count');
    }

    public function getWeight(): float
    {
        return $this->getAttributeValue('weight');
    }

    public function incrementCount(
        int $amount
    ): void {
        $this->increment('count', $amount);
    }

    public function decrementCount(
        int $amount
    ): void {
        $this->decrement('count', $amount);
    }

    public function incrementWeight(
        float $amount
    ): void {
        $this->increment('weight', $amount);
    }

    public function decrementWeight(
        float $amount
    ): void {
        $this->decrement('weight', $amount);
    }
}

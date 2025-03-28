<?php

namespace Mydnic\Volet\Models;

use Illuminate\Database\Eloquent\Model;
use Mydnic\Volet\Features\FeatureManager;

class FeedbackMessage extends Model
{
    protected $fillable = [
        'message',
        'category',
        'status',
        'user_info',
    ];

    protected $casts = [
        'user_info' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('volet.feedback-messages.table'));
    }

    public function getCategory(): ?array
    {
        $feature = app(FeatureManager::class)->getFeature('feedback-messages');
        if (! $feature) {
            return null;
        }

        return collect($feature->getConfig()['categories'])
            ->firstWhere('slug', $this->category);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->status) {
                $model->status = 'new';
            }
        });
    }

    /**
     * Get the category name
     */
    public function getCategoryNameAttribute(): string
    {
        return $this->getCategory()['name'] ?? $this->category;
    }

    /**
     * Get the category icon
     */
    public function getCategoryIconAttribute(): ?string
    {
        return $this->getCategory()['icon'] ?? null;
    }

    /**
     * Mark the feedback as read
     */
    public function markAsRead()
    {
        $this->update(['status' => 'read']);
    }

    /**
     * Mark the feedback as resolved
     */
    public function markAsResolved()
    {
        $this->update(['status' => 'resolved']);
    }

    /**
     * Scope a query to only include unread feedback.
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope a query to only include read feedback.
     */
    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    /**
     * Scope a query to only include resolved feedback.
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    /**
     * Scope a query to filter by category
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}

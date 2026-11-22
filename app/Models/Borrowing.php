<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'member_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date' => 'date',
            'due_date' => 'date',
            'return_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_borrowing')
            ->withPivot('qty');
    }

    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }

    public function isOverdue(): bool
    {
        return ! $this->isReturned() && now()->startOfDay()->gt($this->due_date);
    }
}

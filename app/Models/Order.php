<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'order_number',
        'user_id',
        'book_id',
        'quantity',
        'total_price',
        'payment_method',
        'status',
        'shipping_address',
        'buyer_notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function getOrderCodeAttribute($value)
    {
        return $value ?? $this->order_number ?? ('#ORD-' . str_pad($this->id, 5, '0', STR_PAD_LEFT));
    }

    public function getOrderNumberAttribute($value)
    {
        return $value ?? $this->order_code ?? ('#ORD-' . str_pad($this->id, 5, '0', STR_PAD_LEFT));
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'total_price',
    ];

    /**
     * Boot the model.
     *
     * This method is called when the model is being booted.
     * It registers an event listener to automatically set the `user_id` attribute
     * with the authenticated user's ID when creating a new order.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($order) {
            if (Auth::check())
                $order->user_id = Auth::user()->id;
        });
    }

    /**
     * The products that belong to the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product')->withPivot('quantity');
    }

    /**
     * Get the user that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Calculate the total price of products.
     *
     * @param  array  $products
     * @return float|int|\Illuminate\Http\JsonResponse
     */
    protected function calculateTotalPrice($products)
    {
        $totalPrice = 0;

        foreach ($products as $product) {
            $productModel = Product::findOrFail($product['product_id']);
            $totalPrice += $productModel->price * $product['quantity'];

            // Check for insufficient quantity
            if ($productModel->quantity < $product['quantity']) {
                return response()->json([
                    'message' => 'Insufficient quantity for product: ' . $productModel->name,
                    'status'  => 'field'
                ], 404);
            } else {
                $productModel->quantity -= $product['quantity'];
                $productModel->save();
            }
        }

        return $totalPrice;
    }

    /**
     * Set the total price attribute.
     *
     * @param  float  $value
     * @return void
     */
    public function setTotalPriceAttribute($value)
    {
        $this->attributes['total_price'] = $this->calculateTotalPrice($value);
    }
}

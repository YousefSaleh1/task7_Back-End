<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'user'        => [
                'name'    => $this->user->name,
                'email'   => $this->user->email,
            ],
            'total_price' => $this->total_price,
            'products'    => $this->getProductsWithQuantity(),
        ];
    }

    /**
     * Get the products associated with the order including quantity.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getProductsWithQuantity()
    {
        $products = $this->products->map(function ($product) {
            $quantity = $product->pivot->quantity;

            return [
                'id'          => $product->id,
                'name'        => $product->name,
                'description' => $product->description,
                'price'       => $product->price,
                'quantity'    => $quantity,
            ];
        });

        return $products;
    }
}

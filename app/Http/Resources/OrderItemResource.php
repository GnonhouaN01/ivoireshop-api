<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'productId' => $this->product_id,
            'productName' => $this->product_name,
            'productImage' => $this->product_image,
            'quantity' => $this->quantity,
            'unitPrice' => $this->unit_price,
            'totalPrice' => $this->total_price,
            'options' => $this->options ?? [],
            'product' => $this->whenLoaded('product', new ProductResource($this->product)),
        ];
    }
}

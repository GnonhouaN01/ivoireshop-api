<?php

namespace App\Http\Resources;

use App\Http\Resources\OrderItemResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'orderNumber' => $this->order_number,
            'status' => $this->status,
            'paymentMethod' => $this->payment_method,
            'paymentReference' => $this->payment_reference,
            'paymentStatus' => $this->payment_status,
            'subtotal' => $this->subtotal,
            'deliveryFee' => $this->delivery_fee,
            'discountAmount' => $this->discount_amount,
            'total' => $this->total,
            'notes' => $this->notes,
            'shippingAddress' => $this->shipping_address,
            'paidAt' => $this->paid_at?->toDateTimeString(),
            'shippedAt' => $this->shipped_at?->toDateTimeString(),
            'deliveredAt' => $this->delivered_at?->toDateTimeString(),
            'createdAt' => $this->created_at?->toDateTimeString(),
            'updatedAt' => $this->updated_at?->toDateTimeString(),
            'user' => $this->whenLoaded('user', fn () => new UserResource($this->user)),
            'items' => OrderItemResource::collection($this->items),
        ];
    }
}

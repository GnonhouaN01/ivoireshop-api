<?php

namespace App\Http\Resources;

use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'category' => $this->whenLoaded('category', fn () => new CategoryResource($this->category)),
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'shortDescription' => $this->short_description,
            'price' => $this->price,
            'comparePrice' => $this->compare_price,
            'stockQuantity' => $this->stock_quantity,
            'sku' => $this->sku,
            'images' => $this->images ?? [],
            'attributes' => $this->attributes ?? [],
            'isActive' => $this->is_active,
            'isFeatured' => $this->is_featured,
            'avgRating' => $this->avg_rating,
            'reviewsCount' => $this->reviews_count,
            'thumbnail' => $this->thumbnail,
            'createdAt' => $this->created_at?->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

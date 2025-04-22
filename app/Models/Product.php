<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends BaseDummyModel
{
    protected $guarded = null;

    public static function mapApiData(array $apiData): array
    {
        return [
            'title' => $apiData['title'],
            'description' => $apiData['description'],
            'category' => $apiData['category'],
            'price' => $apiData['price'],
            'discount_percentage' => $apiData['discountPercentage'],
            'rating' => $apiData['rating'],
            'stock' => $apiData['stock'],
            'brand' => $apiData['brand'] ?? "",
            'sku' => $apiData['sku'],
            'weight' => $apiData['weight'],
            'width' => $apiData['dimensions']['width'],
            'height' => $apiData['dimensions']['height'],
            'depth' => $apiData['dimensions']['depth'],
            'warranty_information' => $apiData['warrantyInformation'],
            'shipping_information' => $apiData['shippingInformation'],
            'availability_status' => $apiData['availabilityStatus'],
            'return_policy' => $apiData['returnPolicy'],
            'minimum_order_quantity' => $apiData['minimumOrderQuantity'],
            'thumbnail' => $apiData['thumbnail'],
            'images' => $apiData['images'],
        ];
    }

    public static function getEndpoint(): string
    {
        return '/products';
    }

    public static function getNameEntity(): string
    {
        return 'products';
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!empty($model->images)) {
                foreach ($model->images as $image) {
                    if (request()->hasFile('image')) {
                        $image = request()->file('image');
                        $path = $image->store('products', 'public');

                        $file = Picture::create([
                            'path' => $path,
                            'product_id' => $model->id,
                        ]);
                    }
                }
                unset($model->attributes['images']);
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'stock_quantity',
        'sku',
        'image',
        'gallery',
        'is_featured',
        'is_active',
        'is_archived',
        'category_id',
        'brand_id',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_archived' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2'
    ];

    // Add the brand relationship method
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Check if product has variants
    public function getHasVariantsAttribute()
    {
        return $this->variants()->exists();
    }

    public function getCurrentPriceAttribute()
    {
        if ($this->has_variants) {
            // Return null or minimum price if has variants
            $minPrice = $this->variants->min('current_price');
            return $minPrice ?: $this->price;
        }
        return $this->sale_price ?? $this->price;
    }
    
    /**
     * Check if a specific size is in stock
     */
    public function isSizeInStock($size)
    {
        // If product has variants, check variant stock
        if ($this->has_variants) {
            $variant = $this->variants->where('size', $size)->first();
            return $variant && $variant->stock_quantity > 0;
        }

        // For products without variants, check general stock
        return $this->stock_quantity > 0;
    }

    /**
     * Get stock quantity for a specific size
     */
    public function getStockForSize($size)
    {
        // If product has variants, get variant stock
        if ($this->has_variants) {
            $variant = $this->variants->where('size', $size)->first();
            return $variant ? $variant->stock_quantity : 0;
        }

        // For products without variants, return general stock
        return $this->stock_quantity;
    }

    /**
     * Get all available sizes from variants
     */
    public function getAllSizesAttribute()
    {
        if ($this->has_variants) {
            return $this->variants->pluck('size')->filter()->toArray();
        }
        
        return ['One Size'];
    }

    public function getHasDiscountAttribute()
    {
        if ($this->has_variants) {
            return $this->variants->contains('has_discount', true);
        }
        return !is_null($this->sale_price) && $this->sale_price < $this->price;
    }

    /**
     * Calculate discount percentage for products with variants
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->has_discount) {
            return 0;
        }
        
        if ($this->has_variants) {
            // For products with variants, calculate based on variant prices
            $discounts = $this->variants->filter(function($variant) {
                return !is_null($variant->sale_price) && $variant->sale_price < $variant->price;
            })->map(function($variant) {
                if ($variant->price > 0) {
                    return round((($variant->price - $variant->sale_price) / $variant->price) * 100);
                }
                return 0;
            });
            
            return $discounts->isNotEmpty() ? $discounts->max() : 0;
        }
        
        // For products without variants
        if ($this->price > 0 && $this->sale_price < $this->price) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        
        return 0;
    }

    public function getImageUrlAttribute()
    {
        if ($this->image && filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }
        
        return 'https://picsum.photos/400/300?random=' . uniqid();
    }

    // Get total stock across all variants
    public function getTotalStockAttribute()
    {
        if ($this->has_variants) {
            return $this->variants->sum('stock_quantity');
        }
        
        return $this->stock_quantity;
    }

    // Check if product has any stock
    public function getInStockAttribute()
    {
        return $this->total_stock > 0;
    }

    // Get variant names for display
    public function getVariantNamesAttribute()
    {
        if ($this->has_variants) {
            return $this->variants->pluck('variant_name')->toArray();
        }
        
        return [];
    }

    /**
     * Get sale price for display
     */
    public function getSalePriceAttribute($value)
    {
        if ($this->has_variants) {
            $minSalePrice = $this->variants->min('sale_price');
            $minPrice = $this->variants->min('price');
            
            // If there's a sale price lower than regular price, return it
            if ($minSalePrice && $minSalePrice < $minPrice) {
                return $minSalePrice;
            }
            return $value;
        }
        return $value;
    }

    /**
     * Get price for display
     */
    public function getPriceAttribute($value)
    {
        if ($this->has_variants) {
            return $this->variants->min('price') ?: $value;
        }
        return $value;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('is_archived', false)
                    ->whereHas('category', function($q) {
                        $q->where('is_active', true);
                    });
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%")
              ->orWhereHas('variants', function($q) use ($search) {
                  $q->where('variant_name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
              });
        });
    }

    public function scopeFilterByCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }

    public function scopeFilterByStatus($query, $status)
    {
        switch ($status) {
            case 'active':
                return $query->where('is_active', true)
                            ->where('is_archived', false)
                            ->whereHas('category', function($q) {
                                $q->where('is_active', true);
                            });
            case 'inactive':
                return $query->where(function($q) {
                    $q->where('is_active', false)
                      ->orWhereHas('category', function($q2) {
                          $q2->where('is_active', false);
                      });
                })->where('is_archived', false);
            case 'archived':
                return $query->where('is_archived', true);
            case 'featured':
                return $query->where('is_featured', true)
                            ->where('is_archived', false)
                            ->whereHas('category', function($q) {
                                $q->where('is_active', true);
                            });
            case 'all':
                return $query;
            default:
                return $query->where('is_archived', false);
        }
    }

    public function getIsEffectivelyInactiveAttribute()
    {
        return !$this->is_active || !$this->category->is_active;
    }

    public function archive()
    {
        $this->update(['is_archived' => true]);
    }

    public function unarchive()
    {
        $this->update(['is_archived' => false]);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Update total stock based on variants (for backward compatibility)
    public function updateTotalStock()
    {
        if ($this->has_variants) {
            $this->update([
                'stock_quantity' => $this->variants->sum('stock_quantity')
            ]);
        }
    }

    // Generate SKU for variants
    public function generateVariantSku($variantName)
    {
        $baseSku = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $this->name), 0, 6));
        $variantCode = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $variantName), 0, 3));
        return "{$baseSku}-{$variantCode}-" . strtoupper(Str::random(4));
    }

    /**
     * Get total price for cart items (used in cart relationships)
     */
    public function getTotalPriceAttribute()
    {
        return $this->current_price * ($this->pivot->quantity ?? 1);
    }

    /**
     * Get available sizes as string for display
     */
    public function getAvailableSizesAttribute()
    {
        if ($this->has_variants) {
            $sizes = $this->variants->pluck('size')->filter()->toArray();
            return $sizes ? implode(', ', $sizes) : 'One Size';
        }

        return 'One Size';
    }

    // Rating relationships and methods
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?: 0;
    }

    public function getTotalRatingsAttribute()
    {
        return $this->ratings()->count();
    }

    // Stock relationships
    public function stockIns()
    {
        return $this->hasMany(StockIn::class);
    }

    public function stockOuts()
    {
        return $this->hasMany(StockOut::class);
    }

    public function stockMovements()
    {
        // This combines both stock in and out
        return $this->stockIns->merge($this->stockOuts);
    }

    /**
     * ===========================================
     * RATING & REVIEW METHODS (NEWLY ADDED)
     * ===========================================
     */

    /**
     * Check if user has purchased this product (any order, delivered or not)
     */
    public function purchasedBy($user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return false;
        }

        return \App\Models\OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('product_id', $this->id)->exists();
    }

    /**
     * Check if user has purchased and received this product (delivered orders only)
     */
    public function purchasedAndReceivedBy($user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return false;
        }

        return \App\Models\OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('order_status', 'delivered');
        })->where('product_id', $this->id)->exists();
    }

    /**
     * Check if user has already rated this product (any order)
     */
    public function ratedBy($user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return false;
        }

        return $this->ratings()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if user can review this product
     * (Has purchased and received the product)
     */
    public function canUserReview($user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return false;
        }

        // Check if user has any delivered order containing this product
        return \App\Models\Order::where('user_id', $user->id)
            ->where('order_status', 'delivered')
            ->whereHas('items', function($query) {
                $query->where('product_id', $this->id);
            })
            ->exists();
    }

    /**
     * Get user's delivered orders containing this product
     */
    public function getUserDeliveredOrders($user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return collect();
        }

        return \App\Models\Order::where('user_id', $user->id)
            ->where('order_status', 'delivered')
            ->whereHas('items', function($query) {
                $query->where('product_id', $this->id);
            })
            ->with(['items' => function($query) {
                $query->where('product_id', $this->id);
            }])
            ->latest()
            ->get();
    }

    /**
     * Get user's rating for specific order
     */
    public function getUserRatingForOrder($orderId, $user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return null;
        }

        return $this->ratings()
            ->where('user_id', $user->id)
            ->where('order_id', $orderId)
            ->first();
    }

    /**
     * Check if user has any rating for any order of this product
     */
    public function userHasAnyRating($user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return false;
        }

        return $this->ratings()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Get all user's ratings for this product
     */
    public function getUserRatings($user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return collect();
        }

        return $this->ratings()
            ->where('user_id', $user->id)
            ->with('order')
            ->latest()
            ->get();
    }

    /**
     * Get the count of user's delivered orders for this product
     */
    public function getUserDeliveredOrdersCount($user = null)
    {
        return $this->getUserDeliveredOrders($user)->count();
    }

    /**
     * Get user's rating count for this product
     */
    public function getUserRatingCount($user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return 0;
        }

        return $this->ratings()->where('user_id', $user->id)->count();
    }

    /**
     * Check if user can leave a new review (has delivered orders without reviews)
     */
    public function canLeaveNewReview($user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return false;
        }

        $deliveredOrders = $this->getUserDeliveredOrders($user);
        $ratedOrders = $this->getUserRatings($user)->pluck('order_id')->toArray();

        // Count orders that haven't been rated yet
        $unratedOrders = $deliveredOrders->filter(function($order) use ($ratedOrders) {
            return !in_array($order->id, $ratedOrders);
        });

        return $unratedOrders->count() > 0;
    }

    /**
     * Get orders that user can still review (delivered but not rated)
     */
    public function getOrdersForReview($user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return collect();
        }

        $deliveredOrders = $this->getUserDeliveredOrders($user);
        $ratedOrders = $this->getUserRatings($user)->pluck('order_id')->toArray();

        return $deliveredOrders->filter(function($order) use ($ratedOrders) {
            return !in_array($order->id, $ratedOrders);
        });
    }

    /**
     * Get orders that user has already reviewed
     */
    public function getReviewedOrders($user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return collect();
        }

        $ratings = $this->getUserRatings($user);
        $reviewedOrderIds = $ratings->pluck('order_id')->toArray();
        
        return \App\Models\Order::whereIn('id', $reviewedOrderIds)
            ->with(['items' => function($query) {
                $query->where('product_id', $this->id);
            }])
            ->latest()
            ->get();
    }

    /**
     * Get orders that have been reviewed (for admin purposes)
     */
    public function getReviewedOrdersWithUser()
    {
        return \App\Models\Order::whereHas('items', function($query) {
                $query->where('product_id', $this->id);
            })
            ->whereHas('ratings', function($query) {
                $query->where('product_id', $this->id);
            })
            ->with(['user', 'items' => function($query) {
                $query->where('product_id', $this->id);
            }, 'ratings' => function($query) {
                $query->where('product_id', $this->id);
            }])
            ->latest()
            ->get();
    }

    /**
     * Get orders that haven't been reviewed yet (for admin purposes)
     */
    public function getUnreviewedOrders()
    {
        return \App\Models\Order::whereHas('items', function($query) {
                $query->where('product_id', $this->id);
            })
            ->where('order_status', 'delivered')
            ->whereDoesntHave('ratings', function($query) {
                $query->where('product_id', $this->id);
            })
            ->with(['user', 'items' => function($query) {
                $query->where('product_id', $this->id);
            }])
            ->latest()
            ->get();
    }

    /**
     * Calculate the rating distribution (how many 1-star, 2-star, etc.)
     */
    public function getRatingDistribution()
    {
        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = $this->ratings()->where('rating', $i)->count();
        }
        return $distribution;
    }

    /**
     * Get rating distribution percentages
     */
    public function getRatingDistributionPercentages()
    {
        $distribution = $this->getRatingDistribution();
        $totalRatings = $this->total_ratings;
        
        if ($totalRatings === 0) {
            return array_fill(1, 5, 0);
        }
        
        $percentages = [];
        foreach ($distribution as $rating => $count) {
            $percentages[$rating] = round(($count / $totalRatings) * 100, 1);
        }
        
        return $percentages;
    }

    /**
     * Get recent reviews (last 10)
     */
    public function getRecentReviews($limit = 10)
    {
        return $this->ratings()
            ->with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get reviews with text (not just star ratings)
     */
    public function getReviewsWithText()
    {
        return $this->ratings()
            ->whereNotNull('review')
            ->where('review', '!=', '')
            ->with('user')
            ->latest()
            ->get();
    }

    /**
     * Get helpful reviews (with most likes or positive feedback)
     * This assumes you have a 'helpful_count' field or similar
     */
    public function getMostHelpfulReviews($limit = 5)
    {
        return $this->ratings()
            ->with('user')
            ->orderBy('helpful_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Check if user has reviewed a specific order
     */
    public function hasUserReviewedOrder($orderId, $user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return false;
        }

        return $this->ratings()
            ->where('user_id', $user->id)
            ->where('order_id', $orderId)
            ->exists();
    }

    /**
     * Get user's rating for a specific order if it exists
     */
    public function getUserOrderRating($orderId, $user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return null;
        }

        return $this->ratings()
            ->where('user_id', $user->id)
            ->where('order_id', $orderId)
            ->first();
    }

    /**
     * Get all orders where user purchased this product (regardless of status)
     */
    public function getAllUserOrders($user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return collect();
        }

        return \App\Models\Order::where('user_id', $user->id)
            ->whereHas('items', function($query) {
                $query->where('product_id', $this->id);
            })
            ->with(['items' => function($query) {
                $query->where('product_id', $this->id);
            }])
            ->latest()
            ->get();
    }
}
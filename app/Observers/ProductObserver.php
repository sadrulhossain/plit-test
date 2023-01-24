<?php

namespace App\Observers;

use App\Product;
use App\ProductLog;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function saving(Product $product)
    {
        $product->created_by = Auth::user()->id ?? 0;
        $product->updated_by = Auth::user()->id ?? 0;
    }
    public function saved(Product $product)
    {
        if ($product->wasRecentlyCreated == true) {
            // Data was just created
            $action = '1';
        } else {
            // Data was updated
            $action = '2';
        }

        ProductLog::insert([
            'product_id' => $product->id,
            'action' => $action,
            'taken_by' => Auth::user()->id ?? 0,
            'taken_at' => date("Y-m-d H:i:s"),
        ]);
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}

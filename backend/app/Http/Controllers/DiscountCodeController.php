<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    


public function checkDiscountStatus(Request $request)

{
    $discountCode = $request->input('discount_code');

    $discount = DiscountCode::where('code', $discountCode)->first();

    if ($discount && $discount->active) {
        $percent = $discount->percent;
        $productPrice = 115; // Przyjmujemy, że cena produktu to $100

        $discountedPrice = $productPrice - ($productPrice * $percent / 100);

        return response()->json([
            'message' => 'Kod rabatowy dodany',
            'discountPercent' => $percent,
            'discountedPrice' => $discountedPrice,
        ]);
    } else {
        return response()->json(['message' => 'Kod rabatowy nie istnieje lub jest nieaktywny'], 404);
    }

    // public function checkPercent (Request $request)
    // {
    //     $request->validate([
    //         'percent' => 'required|numeric|min:0|max:100'

    //         ]);

}

}

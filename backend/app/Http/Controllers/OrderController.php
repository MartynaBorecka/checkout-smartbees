<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    
    public function submitData(Request $request)
    {   
        $validatedData = $request->validate([
            'login' => 'required|max:255|alpha_dash',
            'firstname' => 'required|max:255|alpha',
            'surname' => 'required|max:255|alpha',
            'country' => 'required|max:255|alpha_dash',
            'street' => 'required|max:255',
            'postalCode' => 'required',
            'city' => 'required|max:255|alpha_dash',
            'phoneNumber' => 'required|max:9',
            'password' => 'required|min:6|max:255|confirmed',
            'password_confirmation' => 'required',
            'inputStatute' => 'required',
            'deliveryMethod' => 'required',
            'paymentMethod' => 'required',

            'checkedOtherAddress' => 'in:true,false',
            'newCountry' => 'required_if:checkedOtherAddress,true|nullable|alpha_dash',
            'newStreet' => 'required_if:checkedOtherAddress,true|nullable',
            'newCity' => 'required_if:checkedOtherAddress,true|nullable|alpha',
            'newPostalCode' => 'required_if:checkedOtherAddress,true|nullable',

            ]);

        $checkedOtherAddress = filter_var($request->input('checkedOtherAddress'), FILTER_VALIDATE_BOOLEAN);

        if($checkedOtherAddress) {
            $address = new Address();
            $address->country = $request->input('newCountry');
            $address->street = $request->input('newStreet');
            $address->postal_code = $request->input('newPostalCode');
            $address->city = $request->input('newCity');
            $address->save();
            } else {
                $address = new Address();
                $address->country = $request->input('country');
                $address->street = $request->input('street');
                $address->postal_code = $request->input('postalCode');
                $address->city = $request->input('city');
                $address->save();
            }

        $createAccount = filter_var($request->input('createAccount'), FILTER_VALIDATE_BOOLEAN);
        if($createAccount) {
            $user = new User();
            $user->name = $request->input('login');
            $user->password = $request->input('password');
            $user->firstname = $request->input('firstname');
            $user->surname = $request->input('surname');
            $user->phone_number = $request->input('phoneNumber');
            $user->address_id = $address->id;
            $user->save();
            $userId = $user->id;
            } else {
                $userId = 1;
            }

        $order = new Order();
        $order->amount = 1;
        $order->users_id = $userId;
        $order->address_id = $address->id;
        $order->delivery_id = $request->input('deliveryMethod');
        $order->payments_id = $request->input('paymentMethod');
        $order->products_id = 2;
        
        $order->save();

        $orderNumber = '#' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
        
        
        return response()->json([
            'checkIcon' => 'fa-regular fa-circle-check',
            'message' => 'Dziękujemy za złożenie zamówienia !',
            'message2' => 'Twój numer zamówienia: ' . $orderNumber,
            'button' => 'Wróć do sklepu',
        ], 201);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\UserAccount;
use App\Product;
use Validator;
use Hash;
use Str;

class ProductController extends BaseController
{
    public function order(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'quantity' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(['Validation Error.' => $validator->errors()], 401);
        }

        //check stocks
        $stocks = Product::find($request['product_id']);
        if($stocks->quantity < $request['quantity']){
            return $this->sendError('Order failed.', ['Error' => 'Failed to order this product due to unavailability of the stock.'], 400);
        } else {
            $stocks->quantity = $stocks->quantity - $request['quantity'];
            $stocks->update();

            return $this->sendResponse('Order success.', ['data' => 'You have successfully ordered this product.'], 201);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    public function index(){
        $orders = Orders::where('user_id',Auth::user()->id)->orderBy('id','desc')->get();


        return response([
            'success' => true,
            'orders' => $orders
        ],200);
    }

    public function store(Request $request){
        logger($request->all());

        $this->validate($request, [
            'item' => 'required|min:2|max:30',
            'quantity' => 'required|numeric'
        ]);

       $addOrders = Orders::create([
        'user_id' => Auth::user()->id,
        'item' => $request->item,
        'quantity' => $request->quantity
       ]);

       return response([
        'success' => true,
        'data' => $addOrders
    ],200);

    }


    public function update(Request $request,$id){
        $request->validate([
            'item' => 'required',
            'quantity' => 'required|numeric'
        ]);
       $updatedOrder = Orders::where('id',$id)->update($request->only('item','quantity'));

       return response([
        'success' => true,
        'data' => $updatedOrder
       ]);
    }

    public function destroy($id){
        Orders::where('id',$id)->delete();

        return response([
            'success' => true,
        ],200);
    }
}

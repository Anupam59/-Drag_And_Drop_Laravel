<?php

namespace App\Http\Controllers;
use App\Models\order;

use Illuminate\Http\Request;

class orderController extends Controller
{
    public function index() { 
        $posts = order::orderBy('order','ASC')->get();
        return view('Order',compact('posts'));
    }
    
    public function update(Request $request){
        
        $posts = order::all();

        foreach ($posts as $post) {
            foreach ($request->order as $order) {
                if ($order['id'] == $post->id) {
                    $post->update(['order' => $order['position']]);
                }
            }
        }
        return response('Update Successfully.', 200);
    }
}

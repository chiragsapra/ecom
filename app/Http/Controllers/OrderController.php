<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderProducts;
use App\Models\Product;
use Log;
use DB;

class OrderController extends Controller {

    public function __construct() {
        $this->orders = new Order();
        $this->product = new Product();
        $this->orderproducts = new OrderProducts();
    }

    public function orders() {
        $master = [];
        $orders = Order::all();
        foreach ($orders as $order) {
            $data = [];
            $data['id'] = $order->id;
            $data['email'] = $order->email;
            $data['total'] = $order->total;
            $query = DB::table('orderProducts')
                ->select('*')
                ->where('orderid', '=', $order->id)
                ->get()->toArray();
            foreach ($query as $val) {
                $productData = [];
                $productData['name'] = $this->product->find($val->productid)->productname;
                $productData['price'] = $this->product->find($val->productid)->price;
                $data['product'][] = $productData;
            }
            $master[] = $data;
        }
        return response()->json($master, 200);
    }

    public function create(Request $request) {
        $email = $request->email;
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $query = DB::table('orders')
                ->select('*')
                ->where('email', '=', $email)
                ->get()->toArray();
            if (empty($query)) {
                $order = $this->orders;
                $order->email = $request->email;
                $order->save();
                Log::channel('command')->info("Order Create:" . $email);
                return response()->json('Order created successfully', 201);
            } else {
                return response()->json('Order is already created on this email', 406);
            }
        } else {
            return response()->json('Email is not valid', 406);
        }
    }

    public function add(Request $request, $id) {
        $product_id = $request['product_id'];
        $product = $this->product->find($product_id);
        $order = $this->orders->find($id);
        $total = $order->total;
        if ($product && $order) {
            $orderproducts = $this->orderproducts;
            $orderproducts->orderid = $id;
            $orderproducts->productid = $product_id;
            $orderproducts->save();
            Log::channel('command')->info("Product Add:" . $id . " " . $product_id);
            $order->total = $total + $product->price;
            $order->save();
        }
        return response()->json('Product added to order successfully', 201);
    }

    public function pay($id) {
        $order = $this->orders->find($id);
        if (!$order->status) {
            $post = [
                'order_id' => (int)$id,
                'customer_email' => $order->email,
                'value' => $order->total,
            ];

            $ch = curl_init('https://superpay.view.agentur-loop.com/pay');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));

            $response = json_decode(curl_exec($ch));

            curl_close($ch);
            Log::channel('command')->info("Payment:" . $id . " " . $response->message);
            if ($response->message == 'Payment Successful') {
                $order->status = 1;
                $order->save();
            }

            return response()->json($response->message, 200);
        }
    }

    public function delete($id) {
        $this->orders->destroy($id);
        $query = DB::table('orderProducts')
        ->select('*')
            ->where('orderid', '=', $id)
            ->get()->toArray();
        foreach ($query as $val) {
            $this->orderproducts->destroy($val->id);
        }
        Log::channel('command')->info("Order " . $id . " is deleted.");
        return response()->json('Order Delted successfully', 204);
    }
}

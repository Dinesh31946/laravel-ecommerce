<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    public function home()
    {
        $products = Product::get();
        $new_products = Product::limit(6)->latest()->get();
        return view('front.home', compact('products', 'new_products'));
    }

    public function specialOffer()
    {
        return view('front.specialOffer');
    }

    public function delivery()
    {
        return view('front.delivery');
    }

    public function contact()
    {
        return view('front.contact');
    }

    public function cart()
    {
        return view('front.cart');
    }

    public function productView(Request $request)
    {
        $id = $request->id;
        $product = Product::where('id', $id)->with('ProductDetails')->first();
        $category_id = $product->category_id;
        $related_products = Product::where('category_id', $category_id)->get();
        return view('front.productView', compact('id', 'product', 'related_products'));
    }

    public function user_login(){
        return view('front.login');
    }

    public function loginCheck(Request $request){
        $data = array(
            'email' => $request->email,
            'password' => $request->password
        );

        if(Auth::attempt($data)){
            return redirect()->route('home');
        }else{
            return back()->withErrors(['message' => 'invalid email and password']);
        }
    }

    public function user_register(){
        return view('front.register');
    }

    public function user_store(Request $request){
        $data = array(
            'name' => $request->first_name.' '.$request->last_nane,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => 'user'
        );

        $user = User::create($data);
        return redirect()->route('user_login');
    }

    public function makeLogout(){
        Auth::logout();
        return redirect()->route('user_login');
    }
}

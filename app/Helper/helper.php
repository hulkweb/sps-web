<?php
use App\Models\Cart;
use App\Models\Charge;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Favorite;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;


// business setting
if (!function_exists('getSetting')) {
    function getSetting($key)
    {
        $bs = Setting::where('key', $key)->first();
        if ($bs) {
            return $bs->value;
        }
        return null;
    }
}

if (!function_exists('uploads')) {
    function uploads($path=null)
    {

        if ($path==null) {
            return config('app.upload_url');
        }else{
            return config('app.upload_url').'/'.$path;
        }

    }
}


if (!function_exists('isCart')) {
    function isCart($product_id, $user_id)
    {
       $cart = Cart::where(['user_id'=>$user_id, 'product_id'=>$product_id])->first();
       if($cart){
          return true;
       }else{
          return false;
       }
    }
}


if (!function_exists('tableCount')) {
    function tableCount($table, $column=null, $value=null, $status=true)
    {
        if($status){
            if($column==null && $value==null){
                $count = DB::table($table)->where(['status'=>1])->count();

            }else{

                $count = DB::table($table)->where([$column=>$value, 'status'=>1])->count();
            }
        }else{
            if($column==null && $value==null){
                $count = DB::table($table)->count();

            }else{

                $count = DB::table($table)->where([$column=>$value])->count();
            }
        }
         return $count;
    }
}

if (!function_exists('totalRevenue')) {
    function totalRevenue()
    {
      $amount = Payment::sum('amount');
      return 'Rs '.$amount;
    }
}



if (!function_exists('isFavorite')) {
    function isFavorite($product_id, $user_id)
    {
       $favorite = Favorite::where(['user_id'=>$user_id, 'product_id'=>$product_id])->first();
       if($favorite){
          return true;
       }else{
          return false;
       }
    }
}


if (!function_exists('cartTotal')) {
    function cartTotal()
    {
        $cartItems = Cart::where('user_id', auth()->user()->id)->with('product', 'variation')->get();
        $subtotal = 0;
        $total = 0;
        $flattax = 0;
        $percenttax = 0;
        if(count($cartItems)>0){
            $taxes = Charge::where('status',1)->get();

            foreach($cartItems as $cart){
               $subtotal +=  (int)$cart->quantity * (float)optional($cart->variation)->price;
            }

            foreach($taxes as $tax){
                if($tax->type == 'flat'){
                    $flattax +=  (float)$tax->value;
                }else{
                    if($tax->value != 0){
                        $percenttax +=  ($subtotal*(float)$tax->value)/100;
                    }
                }
             }
             $alltax = $percenttax + $flattax;
          $total = $subtotal + $alltax;
          return $total;
    }
}
}


if (!function_exists('taxTotal')) {
    function taxTotal()
    {
        $cartItems = Cart::where('user_id', auth()->user()->id)->with('product', 'variation')->get();
        $subtotal = 0;
        $total = 0;
        $flattax = 0;
        $percenttax = 0;
        if(count($cartItems)>0){
            $taxes = Charge::where('status',1)->get();

            foreach($cartItems as $cart){
               $subtotal +=  (int)$cart->quantity * (float)optional($cart->variation)->price;
            }

            foreach($taxes as $tax){
                if($tax->type == 'flat'){
                    $flattax +=  (float)$tax->value;
                }else{
                    if($tax->value != 0){
                        $percenttax +=  ($subtotal*(float)$tax->value)/100;
                    }
                }
             }
             $alltax = $percenttax + $flattax;
          return $alltax;
    }
}
}


if (!function_exists('cartSubTotal')) {
    function cartSubtotal()
    {
        $cartItems = Cart::where('user_id', auth()->user()->id)->with('product', 'variation')->get();

        $subtotal = 0;
        if(count($cartItems)>0){
             foreach($cartItems as $cart){
               $subtotal +=  (int)$cart->quantity * (float)optional($cart->variation)->price;
            }
          return $subtotal;
    }
}
}



if (!function_exists('sendSMS')) {
    function sendSMS($to, $otp)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://wappify.io/api/create-message',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array(
          'appkey' => 'ceb2ce76-cf23-4b57-94ea-8aba062ae4cb',
          'authkey' => 'nEM6VYmhFp5wSKV5NIGJTJFr4w62kdOLYA493V8AGeIq3dXBI9',
          'to' => '91'.$to,
          'message' => $otp,
          'sandbox' => 'false'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

}



if(!function_exists('sendMail')){
    function sendMail($email,$type,$data){
        $mailData = [
             'page' => $type,
             'data' => $data
        ];
        $mail = Mail::to($email)->send(new NewMail($mailData));
    }
}

function send_push_notif_to_device($user_id, $data)
{
	//  $user = User::find($user_id);
	// if(isset($user->fcm_token)){
    //  $fcm_token = $user->fcm_token;

    // $n_data1 = [
    //     "title" => $data['title'],
    //     "body" => $data['description'],
    //     "image" => $data['image'],
    // ];

    $n_data2 = [
        "type_id" => (string) $data['type_id'],
        "type" => (string) $data['type'],
        "date_time" => date("Y-m-d H:i:s"),
    ];

    // $url = "https://fcm.googleapis.com/v1/projects/satyakabir-chat/messages:send";

    // $header = [
    //     "Authorization: Bearer " . getAccessTokenFromJson(url('public/satyakabir-chat-firebase-adminsdk-84v01-b034d4fe50.json')),
    //     "Content-Type: application/json",
    // ];

    // $postdata = [
    //     'message' => [
    //         'token' => $fcm_token,
    //         'notification' => $n_data1,
    //         'data' => $n_data2,
    //       //  'sound' => 'sound',
    //     ]
    // ];

    // $ch = curl_init();
    // $timeout = 120;
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));
    // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    // // Get URL content
    // $result = curl_exec($ch);
    // // close handle to release resources
    // curl_close($ch);

    // // Handle the response if needed
    // $response = json_decode($result, true);

    // Save notification in the database
    $notification = new Notification;
    $notification->user_id = $user_id;
    $notification->type = $data['type'];
    $notification->title = $data['title'];
    $notification->message = $data['description'];
    $notification->image = $data['image'];
    $notification->data = json_encode($n_data2);
    $notification->save();



	// }
    return true;
}

function getAccessTokenFromJson($jsonFilePath)
{
    $json = json_decode(file_get_contents($jsonFilePath), true);

    $jwtHeader = [
        'alg' => 'RS256',
        'typ' => 'JWT'
    ];

    $now = time();
    $jwtClaimSet = [
        'iss' => $json['client_email'],
        'scope' => 'https://www.googleapis.com/auth/cloud-platform',
        'aud' => 'https://oauth2.googleapis.com/token',
        'iat' => $now,
        'exp' => $now + 3600, // 1 hour expiration
    ];

    $jwt = base64_encode(json_encode($jwtHeader)) . '.' . base64_encode(json_encode($jwtClaimSet));
    $signature = '';
    openssl_sign($jwt, $signature, $json['private_key'], 'sha256');
    $jwt .= '.' . base64_encode($signature);

    $response = file_get_contents('https://oauth2.googleapis.com/token', false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]),
        ],
    ]));

    $jsonResponse = json_decode($response, true);
    return $jsonResponse['access_token'];
}


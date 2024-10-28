<?php


namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('backend.setting.index');
    }

    public function store(Request $request)
    {
        if(isset($request->business)){
            $request->validate([

                'email'=>'email:rfc,dns',
                'phone'=>'digits:10',
                'site_name'=>'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',
             ]);
        }

        $input = $request->all();
        foreach($input as $key => $val){
            if($val==null && $key=='_token'){
                continue;
            }
            $bs = Setting::where('key',$key)->first();
            if(!$bs){
                $bs = new Setting();
                $bs->key = $key;
            }
            if($key == 'logo' ||$key == 'favicon'||$key == 'home_banner'){
            if($request->hasfile('logo') ){
                @unlink(uploads($bs->value));
                $bs->value = $request->file('logo')->store('images/business', 'public');
            }
            elseif($request->hasfile('favicon')){
                @unlink(uploads($bs->value));
                $bs->value = $request->file('favicon')->store('images/business', 'public');
            }

            elseif($request->hasfile('home_banner')){
                @unlink(uploads($bs->value));
                $bs->value = $request->file('home_banner')->store('images/business', 'public');
            }

            }else{
                $bs->value = $val;
            }

            $bs->save();
        }
        return redirect()->back()->with('success','Business Setting Updated');

    }

}

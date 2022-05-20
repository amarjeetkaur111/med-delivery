<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Goods;
use App\Models\GoodsType;
use App\Models\Tag;
use App\Requests\ClearRequest;
use App\Models\UserModel;
use App\Models\Pharmacy;
use App\Models\PharmacyUser;
use App\Models\CustomersAddress;
use App\Models\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Validator;
use Mail;
use Illuminate\Support\Str;


class Administration extends Controller
{
     public function Login(Request $req)
    {

        $email = $req->input('email');
        $password = $req->input('password');

//dd(Auth::attempt(['email'=>$email,'password'=>$password]));will only work if table col are lowercase eg password not Password
        $authemail = UserModel::where('Email', '=', $email)->first();
        if($authemail == null)
        {
          return back()->with('login_data','Incorrect Email');
        }
        else
        {
            if(Hash::check($password,$authemail->Password))
               {
                   $req->session()->put('user',$email);
                   $req->session()->put('usertype',$authemail['UserType']);
                   $req->session()->put('username',$authemail['FirstName']);
                   return redirect()->route('visits');
               }
               else
                    return back()->with('login_data','Incorrect Password');
        }
    }

    public function Logout(Request $req)
    {
            $req->session()->flush();
            return  redirect()->route('login');
    }

    public function ShowForgotPasswordForm()
    {
        return view('Administration.Users.ForgotPassword');
    }

    public function SubmitForgotPasswordForm(Request $req)
    {
        $req->validate([
              'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        Mail::send('Administration.Users.ForgotPasswordEmailLink', ['token' => $token], function($message) use($req){
            $message->to($req->email);
            $message->subject('Reset Password');
        });

        $resetpassword = new ResetPassword();
        $resetpassword->Email = $req->email;
        $resetpassword->Token = $token;
        $resetpassword->save();

        return back()->with('message', 'We have e-mailed your password reset link!');
    }

    public function ShowResetPasswordForm($token)
    {
        return view('Administration.Users.ForgotPasswordLink',['token' => $token]);
    }

    public function SubmitResetPasswordForm(Request $req)
    {
          $req->validate([
              'email' => 'required|email|exists:users',
              'password' => 'required|string|min:6|confirmed',
              'password_confirmation' => 'required'
          ]);

          $updatePassword = ResetPassword::where('Email',$req->email)->where('Token',$req->token)->first();

          if(!$updatePassword){
              return back()->withInput()->with('error', 'Invalid token!');
          }

          $user = UserModel::where('Email',$req->email)->update(['Password' => Hash::make($req->password)]);

          ResetPassword::where('Email',$req->email)->delete();

          return redirect()->route('login')->with('message','Your Password Has Been Reset');;
    }

    public function Goods()
    {
        $data = GoodsType::with('Goods')->get();
        return view('Administration.Goods.Goods',['data'=>$data]);
    }
    public function AddGoodsForm()
    {
        $goodstype = GoodsType::all();
        return view('Administration.Goods.AddGoods',['ids'=> $goodstype]);
    }
    public function AddGoods(Request $req)
    {
        $validator = $req->validate([
            'GoodsTypeId' => 'required',
            'GoodsName' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'Cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'Quantity' => 'required|numeric',
        ]);

        $goodstype = new GoodsType();
        $goods = new Goods();

        $goodstype = GoodsType::find($req->input('GoodsTypeId'));
        $goods->GoodsName=$req->input('GoodsName');
        $goods->Cost=$req->input('Cost');
        $goods->Quantity=$req->input('Quantity');

        if($goodstype->Goods()->save($goods))
            $req->session()->flash('msg','Goods Inserted Successfully');
        else
            $req->session()->flash('errormsg','Something went Wrong! Goods Record Not Added');
        return redirect('/administration/goods');
    }

    public function EditGoods($id)
    {
        $id = decval($id);
        $data = Goods::where('GoodsId',$id)->get();
        $goodstype = GoodsType::all();
        return view('Administration.Goods.EditGoods  ',['data'=>$data,'ids'=>$goodstype]);

    }

    public function UpdateGoods(Request $req)
    {
        $validator = $req->validate([
            'GoodsTypeId' => 'required',
            'GoodsName' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'Cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'Quantity' => 'required|numeric',
        ]);

        $goods = new Goods();

        $goods = Goods::find($req->input('GoodsId'));
        $goods->GoodsName=$req->input('GoodsName');
        $goods->GoodsTypeId=$req->input('GoodsTypeId');
        $goods->Cost=$req->input('Cost');
        $goods->Quantity=$req->input('Quantity');

        if($goods->push())
            $req->session()->flash('successmsg','Goods Record Updated Successfully');
        else
            $req->session()->flash('errormsg','Goods Record Not Updated!');
        return redirect('/administration/goods');
    }

    public function DeleteGoods($id)
    {
        $id = decval($id);
        $goods = Goods::with('SchedulerRecurrence')->where('GoodsId', $id)->first()->toArray();
        if($goods['scheduler_recurrence'] == null)
        {
            $goodss = Goods::where('GoodsId', $id)->delete();
            return \Response::json(array("success" => true), 200);
        }
        elseif($goods !== null)
        {
            return \Response::json(array("success" => false), 422);
        }
    }

    public function GoodsType()
    {
        $goodstype = GoodsType::all();
        return view('Administration.Goods.GoodsType  ',['data'=>$goodstype]);
    }

    public function AddGoodsType(Request $req)
    {
        Validator::make($req->all(),[
            'GoodsTypeName' => 'required|regex:/^[\pL\s]+$/u',
        ])->validateWithBag('AddMedType');

        $goodstype = new GoodsType();
        $goodstype->GoodsTypeName = $req->input('GoodsTypeName');
        if($goodstype->save())
            $req->session()->flash('msg','New Goods Type Added Successfully');
        else
            $req->session()->flash('errormsg','Something went Wrong! Goods Type Not Added');
        return redirect('/administration/goods/goods_type');
    }
    public function EditGoodsType($id)
    {
        $id = decval($id);
        $data = GoodsType::find($id);
        return  response()->json(['data' => $data]);
    }
    public function UpdateGoodsType(Request $req)
    {
        Validator::make($req->all(),[
            'EditGoodsTypeName' => 'required|regex:/^[\pL\s]+$/u',
        ])->validateWithBag('EditMedType');

        $goodstype = new GoodsType();
        $goodstype = GoodsType::find($req->input('EditGoodsTypeId'));
        $goodstype->GoodsTypeName=$req->input('EditGoodsTypeName');
        if($goodstype->push())
             $req->session()->flash('successmsg','Goods Type Updated Successfully');
        else
            $req->session()->flash('errormsg','Goods Type Not Updated!');
        return redirect('/administration/goods/goods_type');
    }

    public function DeleteGoodsType($id)
    {
        $id = decval($id);
        $count = 0; $check = 0;
        $goods = Goods::with('SchedulerRecurrence')->where('GoodsTypeId', $id)->get()->toArray();
        if($goods == null)
        {
            $goodstype = GoodsType::with('Goods')->find($id)->delete();
            return \Response::json(array("success" => true), 200);
        }
        else
        {
            foreach($goods as $goods)
            {
                $count++;
                if($goods['scheduler_recurrence'] == null)
                    $check++;
            }
            if($count == $check)
            {
                $goodstype = GoodsType::with('Goods')->find($id)->delete();
                return \Response::json(array("success" => true), 200);
            }
            else
            {
                return \Response::json(array("success" => false), 422);

            }
        }
    }

    public function Tags()
    {
        $tags = Tag::all();
        return view('Administration.Tags.Tags',['data'=>$tags]);
    }
    public function AddTag(Request $req)
    {
        Validator::make($req->all(),[
            'TagName' => 'required',
            'TagDescription' => 'required',
            'TagColor' => 'required',
        ])->validateWithBag('AddTag');

        $Tag = new Tag();
        $Tag->TagName = $req->input('TagName');
        $Tag->TagDescription = $req->input('TagDescription');
        $Tag->TagColor = $req->input('TagColor');
        if($Tag->save())
            $req->session()->flash('msg','Tag Record Added Successfully');
         else
            $req->session()->flash('errormsg','Something went Wrong! Tag Record Not Added');
        return redirect('/administration/tags');
    }
    public function EditTag($id)
    {
        $id = decval($id);
        $data = Tag::find($id);
        return  response()->json(['data' => $data]);
    }
    public function UpdateTag(Request $req)
    {

        Validator::make($req->all(),[
            'EditTagName' => 'required',
            'EditTagDescription' => 'required',
            'EditTagColor' => 'required',
        ])->validateWithBag('EditTag');

        $Tag = new Tag();
        $Tag = Tag::find($req->input('EditTagId'));
        $Tag->TagName = $req->input('EditTagName');
        $Tag->TagDescription = $req->input('EditTagDescription');
        $Tag->TagColor = $req->input('EditTagColor');
        if($Tag->save())
            $req->session()->flash('successmsg','Tag Record Updated Successfully');
        else
            $req->session()->flash('errormsg','Something went Wrong! Tag Record Not Updated');
        return redirect('/administration/tags');
    }

    public function DeleteTag($id)
    {
        $id = decval($id);
        $tag = new Tag();
        $tag = Tag::find($id);
        $tag->delete();
        echo "Tag Deleted";
    }

    public function Users()
    {
        $userdata = UserModel::all();
        $pharmacydata = Pharmacy::all('PharmacyId','PharmacyName');
        $pharmacyuser = PharmacyUser::with('User','Pharmacy')->get();
        // get_d($userdata);
        return view('Administration.Users.Users',['userdata'=>$userdata,'pharmacyuser' => $pharmacyuser,'pharmacydata' => $pharmacydata]);
    }
    public function AddUser(Request $req)
    {
        if($req->input('UserType')=='Admin')
        {
           Validator::make($req->all(),[
                'FirstName' => 'required',
                'LastName' => 'required',
                'Email' => 'required|email|unique:users,Email',
                'PhoneNumber' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
                'Password' => 'required',
            ])->validateWithBag('AddAdmin');

            $user = new UserModel();
            $user->UserType = $req->input('UserType');
            $user->FirstName = $req->input('FirstName');
            $user->LastName = $req->input('LastName');
            $user->Email = $req->input('Email');
            $user->PhoneNumber = $req->input('PhoneNumber');
            $user->Password = Hash::make($req->input('Password'));
            if($user->save())
                $req->session()->flash('msg','New Admin Added Successfully');
            else
                $req->session()->flash('errormsg','Something went Wrong! Admin Record Not Added');
            return redirect('/administration/users');
        }
        elseif($req->input('UserType')=='Pharmacist')
        {
            Validator::make($req->all(),[
                'PharmacyId' => 'required',
                'AddPharmacistFirstName' => 'required',
                'AddPharmacistLastName' => 'required',
                'AddPharmacistEmail' => 'required|email|unique:users,Email',
                'AddPharmacistPhoneNumber' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
                'AddPharmacistPassword' => 'required',
            ])->validateWithBag('AddPharmacy');

            $user = new UserModel();
            $user->UserType = $req->input('UserType');
            $user->FirstName = $req->input('AddPharmacistFirstName');
            $user->LastName = $req->input('AddPharmacistLastName');
            $user->Email = $req->input('AddPharmacistEmail');
            $user->PhoneNumber = $req->input('AddPharmacistPhoneNumber');
            $user->Password = Hash::make($req->input('AddPharmacistPassword'));
            $user->save();
            $lastInsertedId = $user->Id;

            $pharmacyuser = new PharmacyUser();
            $pharmacyuser->PharmacyId= $req->input('PharmacyId');
            if($user->PharmacyUsers()->save($pharmacyuser))
                $req->session()->flash('msg','New Pharmacist Added Successfully');
            else
                $req->session()->flash('errormsg','Something went Wrong! Pharmacist Record Not Added');
            return redirect('/administration/users');
        }
        elseif($req->input('UserType')=='Driver')
        {
           Validator::make($req->all(),[
                'AddDriverFirstName' => 'required',
                'AddDriverLastName' => 'required',
                'AddDriverEmail' => 'required|email|unique:users,Email',
                'AddDriverPhoneNumber' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
                'AddDriverPassword' => 'required',
            ])->validateWithBag('AddDriver');

            $user = new UserModel();
            $user->UserType = $req->input('UserType');
            $user->FirstName = $req->input('AddDriverFirstName');
            $user->LastName = $req->input('AddDriverLastName');
            $user->Email = $req->input('AddDriverEmail');
            $user->PhoneNumber = $req->input('AddDriverPhoneNumber');
            $user->Password = Hash::make($req->input('AddDriverPassword'));
            if($user->save())
                $req->session()->flash('msg','New Driver Added Successfully');
            else
                $req->session()->flash('errormsg','Something went Wrong! Driver Record Not Added');
            return redirect('/administration/users');
        }
    }
    public function EditUser($id)
    {
        $id = decval($id);
        $data = UserModel::find($id);
        $pharmacyuser = PharmacyUser::where('UserId','=',$id)->get();
        // $pharmacyuser = UserModel::with('PharmacyUser')->find();
        return  response()->json(['data' => $data,'pharmacyuser' => $pharmacyuser]);
    }
    public function UpdateUser(Request $req)
    {
        if($req->input('UserType')=='Admin')
        {
            Validator::make($req->all(),[
                'EditAdminFirstName' => 'required',
                'EditAdminLastName' => 'required',
                'EditAdminEmail' => 'required|email',
                'EditAdminPhoneNumber' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
            ])->validateWithBag('EditAdmin');

            $flag = 0;
            $user = new UserModel();
            $id = decval($req->input('EditId'));
            $user = UserModel::find($id);
            $user->UserType = $req->input('UserType');
            $user->FirstName = $req->input('EditAdminFirstName');
            $user->LastName = $req->input('EditAdminLastName');
            $user->Email = $req->input('EditAdminEmail');
            $user->PhoneNumber = $req->input('EditAdminPhoneNumber');
            if($req->input('EditAdminPassword') == '')
            {
                if($user->push())
                    $flag=1;
                else
                    $flag=0;
            }
            else
            {
                $user->Password = Hash::make($req->input('EditAdminPassword'));
                if($user->push())
                    $flag=1;
                else
                    $flag=0;
            }

            if($flag==1)
                $req->session()->flash('successmsg','Admin Record Updated Successfully');
            else
                $req->session()->flash('errormsg','Something went Wrong! Admin Record Not Updated');
            return redirect('/administration/users');
        }
        elseif($req->input('UserType')=='Driver')
        {
            Validator::make($req->all(),[
                'EditDriverFirstName' => 'required',
                'EditDriverLastName' => 'required',
                'EditDriverEmail' => 'required|email',
                'EditDriverPhoneNumber' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
            ])->validateWithBag('EditDriver');


            $flag = 0;

            $user = new UserModel();
            $driverid = decval($req->input('EditDriverId'));
            $user = UserModel::find($driverid);
            $user->UserType = $req->input('UserType');
            $user->FirstName = $req->input('EditDriverFirstName');
            $user->LastName = $req->input('EditDriverLastName');
            $user->Email = $req->input('EditDriverEmail');
            $user->PhoneNumber = $req->input('EditDriverPhoneNumber');

            if($req->input('EditDriverPassword') == '')
            {
                if($user->push())
                    $flag=1;
                else
                    $flag=0;
            }
            else
            {
                $user->Password = Hash::make($req->input('EditDriverPassword'));
                if($user->push())
                    $flag=1;
                else
                    $flag=0;
            }

            if($flag==1)
                $req->session()->flash('successmsg','Driver Record Updated Successfully');
            else
                $req->session()->flash('errormsg','Something went Wrong! Driver Record Not Updated');
            return redirect('/administration/users');
        }
        elseif($req->input('UserType')=='Pharmacist')
        {
            Validator::make($req->all(),[
                'EditPharmacyId' => 'required',
                'EditPharmacistFirstName' => 'required',
                'EditPharmacistLastName' => 'required',
                'EditPharmacistEmail' => 'required|email',
                'EditPharmacistPhoneNumber' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
            ])->validateWithBag('EditPharmacist');

            $flag = 0;
            $user = new UserModel();
            $pharmacistid = decval($req->input('EditPharmacistId'));
            $user = UserModel::find($pharmacistid);
            $user->UserType = $req->input('UserType');
            $user->FirstName = $req->input('EditPharmacistFirstName');
            $user->LastName = $req->input('EditPharmacistLastName');
            $user->Email = $req->input('EditPharmacistEmail');
            $user->PhoneNumber = $req->input('EditPharmacistPhoneNumber');
            $user->PharmacyUsers->PharmacyId= $req->input('EditPharmacyId');

            if($req->input('EditPharmacistPassword') == '')
            {
                if($user->push())
                    $flag=1;
                else
                    $flag=0;
            }
            else
            {
                $user->Password = Hash::make($req->input('EditPharmacistPassword'));
                if($user->push())
                    $flag=1;
                else
                    $flag=0;
            }

            if($flag==1)
                $req->session()->flash('successmsg','Pharmacist Record Updated Successfully');
            else
                $req->session()->flash('errormsg','Something went Wrong! Pharmacist Record Not Updated');
            return redirect('/administration/users');
        }

    }
    public function DeleteUser($id)
    {
        $id = decval($id);
        $user = new UserModel();
        $user = UserModel::with('PharmacyUsers')->find($id);
        $user->delete();
        echo "User Deleted";
    }

     public function Pharmacies()
    {
        $pharmacy = Pharmacy::all();
        return view('Administration.Pharmacies.Pharmacies',['pharmacy'=>$pharmacy]);
    }

    public function AddPharmacy(Request $req)
    {
         Validator::make($req->all(),[
            'PharmacyName' => 'required',
            'PharmacyAddress' => 'required',
            'PharmacyManager' => 'required',
            'PharmacyPhone' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
        ])->validateWithBag('AddPharmacy');

        $pharmacy = new Pharmacy();
        $pharmacy->PharmacyName = $req->input('PharmacyName');
        $pharmacy->PharmacyAddress = $req->input('PharmacyAddress');
        $pharmacy->PharmacyPhone = $req->input('PharmacyPhone');
        $pharmacy->PharmacyManager = $req->input('PharmacyManager');
        if($pharmacy->save())
            $req->session()->flash('msg','New Pharmacy Added Successfully');
        else
            $req->session()->flash('errormsg','Something went Wrong! Pharmacy Record Not Added');
        return redirect('/administration/pharmacies');
    }

    public function EditPharmacy($id)
    {
        $id = decval($id);
        $data = Pharmacy::find($id);
        return  response()->json(['data' => $data]);
    }
    public function UpdatePharmacy(Request $req)
    {
        Validator::make($req->all(),[
            'EditPharmacyName' => 'required',
            'EditPharmacyAddress' => 'required',
            'EditPharmacyManager' => 'required',
            'EditPharmacyPhone' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
        ])->validateWithBag('EditPharmacy');

        $pharmacy = new Pharmacy();
        $pharmacy = Pharmacy::find($req->input('EditPharmacyId'));
        $pharmacy->PharmacyName = $req->input('EditPharmacyName');
        $pharmacy->PharmacyAddress = $req->input('EditPharmacyAddress');
        $pharmacy->PharmacyPhone = $req->input('EditPharmacyPhone');
        $pharmacy->PharmacyManager = $req->input('EditPharmacyManager');
        if($pharmacy->save())
            $req->session()->flash('successmsg','Pharmacy Record Updated Successfully');
        else
            $req->session()->flash('errormsg','Something went Wrong! Pharmacy Record Not Updated');
        return redirect('/administration/pharmacies');
    }

    public function DeletePharmacy($id)
    {
        $id = decval($id);
        $pharmacy = new Pharmacy();
        $pharmacy = Pharmacy::with('PharmacyUsers')->find($id);
        $pharmacy->delete();
        echo "Pharmacy Deleted";
    }
}



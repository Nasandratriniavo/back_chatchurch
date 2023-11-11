<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use \Illuminate\Http\JsonResponse;

class UtilisateurController extends Controller
{
    public function verifier()
    {
        // return UserResource::collection(User::all());
        $mail = 'nasandratra@rhema.admin';
        $name = 'Nasandratra@musicien';

        $check = DB::select('select * from users where name = ?', ['Nasandratra@musicien']);
        if (!($check)) {
            $users = User::create([
                'email' => 'nasandratra@rhema.admin',
                'name' => 'Nasandratra@musicien',
                'type' => 'ADMIN',
                'resp_ilite' => 'Musicien',
                'password' => Hash::make('0000')
            ]);

            return response()->json([
                'status'=>200,
                'message'=>'Inseré'
            ], 200);
        } else {
            return response()->json([
                'status'=>200,
                'message'=>'Déjà'
            ], 200);
        }
    }


    public function login(Request $request)
    {
        $email = $request->input('email');
        // $credentials = $request->validated();

        $val = validator()->make($request->all(), [
            'email' => 'required|email|max:200',
            'password' => 'required|min:4|max:16',
        ]);

        if ($val->fails())
        {
            return response()->json([
                'status'=>422,
                'errors'=>$val->messages(),
            ], 422);
        }
        else
        {
            $credentials = $request->only('email', 'password');

            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'Invalid credentials'], 401);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'Could not create token'], 500);
            }
        }
        
        $user = Auth::user();
        
        return response()->json(
            ['token' => $token, 'user' => $user]
        );
        
    }

    public function savenomID(Request $request)    
    {
        $val = validator()->make($request->all(), [
            'nomid' => 'required|max:200',
        ]);

        if ($val->fails())
        {
            return response()->json([
                'status'=>422,
                'errors'=>$val->messages(),
            ], 422);
        }
        else
        {
            $identite = $request->input('id');   
            $nomid = $request->input('nomid');

            $request = DB::table('users')
              ->where('id', $identite)
              ->update(['name' => $nomid]);
            
            $newdata = DB::table('users')->find($identite);

            return response()->json(['user' => $newdata,]);
        }    
    }


    public function savenomUTIL(Request $request)    
    {
        $val = validator()->make($request->all(), [
            'nomutil' => 'required|max:200',
        ]);

        if ($val->fails())
        {
            return response()->json([
                'status'=>422,
                'errors'=>$val->messages(),
            ], 422);
        }
        else
        {
            $identite = $request->input('id');   
            $nomutil = $request->input('nomutil');

            $request = DB::table('users')
              ->where('id', $identite)
              ->update(['email' => $nomutil]);
            
            $newdata = DB::table('users')->find($identite);

            return response()->json(['user' => $newdata,]);
        }    
    }

    public function checking(Request $request)    
    {
        $val = validator()->make($request->all(), [
            'email' => 'required|email|max:200',
            'password' => 'required|min:4|max:16',
            'newpassword' => 'required|min:4|max:16',
            'confpassword' => 'required|min:4|max:16',
        ]);

        if ($val->fails())
        {
            return response()->json([
                'status'=>422,
                'longueur'=>'Verifier la longueur du champ',
            ], 422);
        }
        else
        { 
            $credentials = $request->only('email', 'password');

            if (JWTAuth::attempt($credentials)) {
                User::where('email', $request->email)->update(['password' => Hash::make($request->confpassword)]);
                return response()->json(['message' => 'Mot de passe modifié'], 200);
            } else {
                return response()->json(['ancien' => 'Ancien mot de passe incorrect'], 401);
            }
        }
    }

    public function allutilisateur()
    {
        $users = User::create([
            'email' => 'president@rhema.user',
            'name' => 'Solofo@conducteur',
            'type' => 'USER',
            'resp_ilite' => 'Conducteur',
            'password' => Hash::make('1234')
        ]);

        $users = User::create([
            'email' => 'technicien@rhema.user',
            'name' => 'Alain@technicien',
            'type' => 'USER',
            'resp_ilite' => 'Technicien',
            'password' => Hash::make('1234')
        ]);

        $users = User::create([
            'email' => 'bishop@rhema.user',
            'name' => 'Neny@pasteur',
            'type' => 'USER',
            'resp_ilite' => 'Pasteur',
            'password' => Hash::make('1234')
        ]);
    }

    public function all_disc(Request $request)
    {
        $val = validator()->make($request->all(), [
            'id' => 'required|max:20',
        ]);

        if ($val->fails())
        {
            return response()->json([
                'status'=>422,
                'errors'=>$val->messages(),
            ], 422);
        }
        else
        {
            $identite = $request->input('id');

            $request = DB::table('users')
              ->where('id', '!=', $identite)
              ->get();

            return response()->json($request);
        }    
    }


}

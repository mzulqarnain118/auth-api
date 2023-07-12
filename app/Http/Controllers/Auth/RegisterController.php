<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\OTPVerificationMail;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
        protected function validator(array $data)
        {
            return Validator::make($data, [
                'parent_email' => ['string', 'email', 'max:255', 'unique:bsc_user'],
                'email' => ['required','string', 'email', 'max:255','unique:bsc_user'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'ITS' => ['required', 'integer', 'min:6', 'unique:bsc_user', 'digits:6'],
                'DOB' => ['required', 'date'],
                'gender' => ['required', 'string','min:1', 'max:1',],
                'jamaat' => ['required', 'string'],
                'role' => ['required', 'integer', 'min:1', 'max:2'],
            ]);
        }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
        protected function sendVerificationEmail(User $user, int $otp = null): bool
        {
            $otp = $otp ?? random_int(1000, 9999);

            try {
                \Log::error('Error sending verification email: ' );
                Mail::to($user->email)->send(new OTPVerificationMail($user, $otp));
                return true;
            } catch (\Exception $e) {
                // Log any exception or error that occurred while sending the email
                // You can customize the logging mechanism as per your application's needs
                \Log::error('Error sending verification email: ' . $e->getMessage());
                return false;
            }
        }

     public function register(Request $request)
    {
           $data = $request->all();

        $validator = $this->validator($data);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'parent_email' => $request->parent_email,
            'parent_name' => $request->parent_name,
           'parent_phone' => $request->parent_phone,
            'bsc_u_password' => Hash::make($request->password),
            'ITS' => $request->ITS,
            'DOB' => $request->DOB,
            'gender' => $request->gender,
            'jamaat' => $request->jamaat,
            'bsc_u_ur_id' =>$request->role,
        ]);
          $token = $user->createToken('authToken')->plainTextToken;
        // 'token' => $token,

            // Generate a random 4-digit OTP
        $otp = random_int(1000, 9999);

        $mail = new OTPVerificationMail($user->name,$user->email, $otp);
        $mail->build();
        \Mail::to($mail->getEmail())->send($mail);
        // Mail::queue('email.otp', [
        //     'name' => $user->name,
        //     'email' => $user->email,
        //     'otp' => $otp,
        // ], new OTPVerificationMail($user->name, $user->email,$otp));


        return response()->json(['user' => $user,'otp' => $otp,], 201);
    }

     public function register1(Request $request)
    {
        $data = $request->all();

        $validator = $this->validator($data);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'parent_email' => $request->parent_email,
            'parent_name' => $request->parent_name,
            'parent_phone' => $request->parent_phone,
            'bsc_u_password' => Hash::make($request->password),
            'ITS' => $request->ITS,
            'DOB' => $request->DOB,
            'gender' => $request->gender,
            'jamaat' => $request->jamaat,
            'bsc_u_ur_id' => $request->role,
        ]);

        // Generate a random 4-digit OTP
        $otp = random_int(1000, 9999);

        // Send the OTP email
        $emailSent = $this->sendVerificationEmail($user, $otp);

        if (!$emailSent) {
            // Handle the case when the email sending fails
            return response()->json(['message' => 'Failed to send verification email.'], 500);
        }
        return response()->json(['user' => $user], 201);
    }

    /**
     * Send the OTP verification email.
     *
     * @param  User  $user
     * @param  int  $otp
     * @return void
     */
    protected function sendVerificationEmail1(User $user, int $otp)
    {
        Mail::to($user->email)->send(new OTPVerificationMail($user, $otp));
    }


    /**
     * Match the OTP sent to the email.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function matchOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:4',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Check if the OTP matches
        if ($request->otp == $user->otp) {
            // OTP matches, proceed with user registration
            $user->verified = true;
            $user->save();

            return response()->json(['message' => 'OTP verified successfully.'], 200);
        }

        return response()->json(['message' => 'OTP does not match.'], 422);
    }




     public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }

     public function getById(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    public function getAll(Request $request)
    {
        $users = User::all();

        return response()->json($users);
    }

    public function updateById(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->all());

        return response()->json($user);
    }

     public function getUserRoles(Request $request)
    {
        $users = UserRole::all();

        return response()->json($users);
    }
}
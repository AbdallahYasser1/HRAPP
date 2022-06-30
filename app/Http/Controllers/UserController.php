<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\AuthResource;
use App\Models\Absence;
use App\Models\Attendance;
use App\Models\Profile;
use App\Models\Requestdb;
use App\Models\Salary\SalaryTerm;
use App\Models\Vacationday;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with(["profile.job_title"])->get();
        if ($users === null) {
            return $this->errorResponse("Users are not existed", 404);
        } else {
            return new UserCollection($users);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $path = '';
        if ($request['role'] == 'Admin' && !Auth::user()->hasPermissionTo('create_account_Admin')) return $this->errorResponse(' The authenticated user is not permitted to perform the requested operation.', 403);
        $hashed_random_password = Str::random(10);
        if ($request->hasFile('image')) {
            //$path=$request->file('photo')->store('public/images');
            $path = cloudinary()->upload($request->file('image')->getRealPath(), $options = ["folder" => "images"])->getSecurePath();
        }
        $user = User::create([
            'id' => $request['id'],
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'birthdate' => $request['birthdate'],
            'shift_id' => $request['shift_id'],
            'password' => $hashed_random_password,
            'can_wfh' => $request['can_wfh'],
            'supervisor' => $request['supervisor'],
        ]);
        $vacationday = Vacationday::create(['user_id' => $request['id'],
            'scheduled' => $request['scheduled'],
            'unscheduled' => $request['unscheduled']]);
        $profile = Profile::create([
            'user_id' => $request['id'],
            'department_id' => $request['department_id'],
            'job__title_id' => $request['job__title_id'],
            'image' => $path == '' ? "https://res.cloudinary.com/dokaaek9w/image/upload/v1653746912/profile_images/IMG-20220403-WA0021_yvig6b.jpg" : $path
        ]);
        $salary_term = SalaryTerm::create([
            'user_id' => $request['id'],
            'salary_agreed' => $request['salary']
        ]);
        $user->assignRole($request['role']);
        $response = ['user' => new AuthResource($user), 'password' => $hashed_random_password];
        return $this->showCustom($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $role = $user->getRoleNames();
        $user = $user::with(['profile', 'vacationday', 'salaryTerm'])->where('id', $user->id)->get();

        if ($user === null) {
            return $this->errorResponse("user not found", 404);
        } else {
            return $this->ShowCustom([$user, "role" => $role], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if ($request['role'] == 'Admin' && !Auth::user()->hasPermissionTo('create_account_Admin')) return $this->errorResponse(' The authenticated user is not permitted to perform the requested operation.', 403);
        if ($user === null) {
            return $this->errorResponse("user not found", 404);
        } else {
            $path = '';
            if ($request->hasFile('image')) {
                //$path=$request->file('photo')->store('public/images');
                $path = cloudinary()->upload($request->file('image')->getRealPath(), $options = ["folder" => "images"])->getSecurePath();
            }
            $User_Request = [
                'name' => $request['name'] == null ? $user->name : $request['name'],
                'email' => $request['email'] == null ? $user->email : $request['email'],
                'phone' => $request['phone'] == null ? $user->phone : $request['phone'],
                'birthdate' => $request['birthdate'] == null ? $user->birthdate : $request['birthdate'],
                'shift_id' => $request['shift_id'] == null ? $user->shift_id : $request['shift_id'],
                'password' => $request['password'] == null ? $user->password : $request['password'],
                'can_wfh' => $request['can_wfh'] == null ? $user->can_wfh : $request['can_wfh'],
                'supervisor' => $request['supervisor'] == null ? $user->supervisor : $request['supervisor']
            ];
            $Profile_Request = [
                'department_id' => $request['department_id'] == null ? $user->profile->department_id : $request['department_id'],
                'job__title_id' => $request['job__title_id'] == null ? $user->profile->job__title_id : $request['job__title_id'],
                'image' => $request['image'] == null ? $user->profile->image : $path

            ];
            $Salary_Request = ['salary_agreed' => $request['salary'] == null ? $user->salaryTerm->salary_agreed : $request['salary']];
            $user->update($User_Request);
            $user->profile()->update($Profile_Request);
            $user->salaryTerm()->update($Salary_Request);
            $user->profile->save();
            $user->syncRoles($request['role'] == null ? $user->roles->pluck('name')[0] : $request['role']);
            $userresult = User::find($user->id);
            $response = ['User' => $userresult, "Profile" => $userresult->profile, "Salary" => $userresult->salaryTerm, "role" => $userresult->roles->pluck('name')[0]];
            return $this->showCustom($response, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user === null) {
            return $this->errorResponse("user not found", 404);
        } else {
            if (Auth::user()->hasRole("HR") && $user->hasAnyRole(['Admin', 'HR'])) {
                return $this->showCustom(['HR Cant Delete Admin\HR User OR Cant delete Admin User by himself'], 403);
            } else if (Auth::id() == $user->id) {
                return $this->showCustom(['User cant delete himself'], 403);
            }
            $user->delete();
            return $this->showCustom(['user deleted'], 200);
        }
    }

    public function ViewAllRequests(Request $request)
    {
        $status = $request->query('status');
        if ($status === null) {
            $requestes = Requestdb::with(['requestable'])
                ->join('users', 'requestdbs.user_id', 'users.id')
                ->where('users.id', '=', Auth::id())
                ->get();
        } else {
            $requestes = Requestdb::with(['requestable'])
                ->join('users', 'requestdbs.user_id', 'users.id')
                ->where('users.id', '=', Auth::id())
                ->where('requestdbs.status', '=', $status)
                ->get();

        }
        return $this->showCustom($requestes, 200);
    }

    function showAllUsersAdmin(Request $request)
    {
        return $this->showAll(User::all(), 200);
    }

    function UserStatus()
    {
        return $this->showCustom(Auth::user()->status, 200);
    }

    function UpdatePassword(LoginUserRequest $request)
    {
        $user = Auth::user();
        if ($user == null) {
            return $this->errorResponse('User Not Found ', 200);
        }
        $user->password = $request->password;
        $user->save();
        auth()->user()->tokens()->delete();
        return response()->json(["message" => "Password has changed succesfully please login again with the new password"], 200);
    }

    public function AdminAttendanceSheet(Request $request)
    {
        $date = $request->query('date');
        $user = $request->query('user');
        $attendance = Attendance::with('user');
        if ($date != null)
            $attendance->where('date', $date);
        if ($user != null)
            $attendance->where('user_id', $user);

        return $attendance->get();
    }

    public function UserAttendanceSheet(Request $request)
    {
        $date = $request->query('date');
        $attendance = Attendance::with('user');
        if ($date != null)
            $attendance->where('date', $date);
        $attendance->where('user_id', Auth::id());

        return $attendance->get();
    }

    public function AdminAbsenceSheet(Request $request)
    {
        $date = $request->query('date');
        $user = $request->query('user');
        $attendance = Absence::with('user');
        if ($date != null)
            $attendance->where('date', $date);
        if ($user != null)
            $attendance->where('user_id', $user);

        return $attendance->get();
    }

    public function UserAbsenceSheet(Request $request)
    {
        $date = $request->query('date');
        $user = $request->query('user');
        $attendance = Absence::with('user');
        if ($date != null)
            $attendance->where('date', $date);

        $attendance->where('user_id', Auth::id());

        return $attendance->get();
    }

    public function birthdays()
    {
// start range 7 days ago
        $start = date('z') + 1;
// end range 7 days from now
        $end = date('z') + 1 + 20;
        //$birthdays = User::whereRaw("DAYOFYEAR(birthdate) BETWEEN $start AND $end")->get();
        $birthdays= User::whereMonth('birthdate' ,'=', Carbon::today()->month)
            ->whereDay('birthdate' ,'>=', Carbon::today()->day)
            ->get();

        return $birthdays;
    }


    public function UpdateUserImage(Request $request)
    {
        $photo = Profile::where('user_id', '=', $request['user_id'])->firstOrFail();

        if ($request->hasFile('image')) {
            $path = cloudinary()->upload($request->file('image')->getRealPath(), $options = ["folder" => "images"])->getSecurePath();
            $photo->update([
                'image' => $path
            ]);
            $photo->save();
        }

        return $this->showCustom($photo);
    }
}

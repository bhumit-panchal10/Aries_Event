<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\State;
use App\Models\TicketTypeMaster;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store', 'updateStatus']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['delete']]);
    }


    /**
     * List User
     * @param Nill
     * @return Array $user
     * @author Shani Singh
     */
    public function index()
    {
        $users = User::select(
            'users.id',
            'users.username',
            'users.first_name',
            'users.email',
            'users.mobile_number',
            'users.status',
            'users.ireportTo',
            'roles.name',
            DB::raw("(select CONCAT(first_name,' ', last_name) from `users` as ru where `users`.ireportTo=ru.id) as strReportTo"),
        )
            ->where('users.id', '!=', '1')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->paginate(25);
        //->toSql();
        //dd($users);
        // $Report = User::where('users.id', '!=', '1')
        //     ->where('ireportTo', 'username')
        //     ->get();
        // dd($Report);
        return view('admin.users.index', ['users' => $users]);
    }

    /**
     * Create User
     * @param Nill
     * @return Array $user
     * @author Shani Singh
     */
    public function create()
    {
        $TicketType = TicketTypeMaster::orderBy('tickettypename', 'asc')->get();
        $roles = Role::where('id', '!=', '1')->get();
        $Manager = User::select(
            'users.id',
            'users.username',
            'users.first_name',
            'users.last_name',
            'users.email',
            'users.mobile_number',
            'users.status',
            'users.role_id',
            'roles.name',
        )
            ->orderBy('id', 'asc')
            ->where('users.role_id', '=', 2)
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->get();

        $states = State::all();
        return view('admin.users.add', ['roles' => $roles, 'states' => $states, 'Manager' => $Manager, 'TicketType' => $TicketType]);
    }

    /**
     * Store User
     * @param Request $request
     * @return View Users
     * @author Shani Singh
     */
    public function store(Request $request)
    {
        //dd($request);
        // // Validations
        $request->validate([
            'email' => 'required|unique:users,email',
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            $user = User::create([
                'username'    => $request->username,
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'address1'     => $request->address1,
                'address2'     => $request->address2,
                'landmark'     => $request->landmark,
                'city'     => $request->city,
                'state'     => $request->state,
                'pincode'     => $request->pincode,
                'password'      => Hash::make($request->password),
                'mobile_number' => $request->mobile_number,
                'secondarymobile' => $request->secondarymobile,
                'role_id'       => $request->role_id,
                'isInsurance'       => $request->isInsurance,
                'status'        => $request->status,
                'ireportTo'        => $request->ireportTo ?? 0,
                'itickettype'        => $request->itickettype ?? 0,
            ]);
            // dd($user);
            // Delete Any Existing Role
            DB::table('model_has_roles')->where('model_id', $user->id)->delete();

            // Assign Role To User
            $user->assignRole($user->role_id);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('users.index')->with('success', 'User Created Successfully.');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Update Status Of User
     * @param Integer $status
     * @return List Page With Success
     * @author Shani Singh
     */
    public function updateStatus($user_id, $status)
    {
        // Validation
        $validate = Validator::make([
            'user_id'   => $user_id,
            'status'    => $status
        ], [
            'user_id'   =>  'required|exists:users,id',
            'status'    =>  'required|in:0,1',
        ]);

        // If Validations Fails
        if ($validate->fails()) {
            return redirect()->route('users.index')->with('error', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Update Status
            User::whereId($user_id)->update(['status' => $status]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('users.index')->with('success', 'User Status Updated Successfully!');
        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Edit User
     * @param Integer $user
     * @return Collection $user
     * @author Shani Singh
     */
    public function edit(Request $request, $id)
    {
        $TicketType = TicketTypeMaster::orderBy('tickettypename', 'asc')->get();
        $user = User::where(['status' => 1, 'id' => $id])->first();
        $roles = Role::where('id', '!=', '1')->get();
        $states = State::all();
        $Manager = User::select(
            'users.id',
            'users.username',
            'users.first_name',
            'users.last_name',
            'users.email',
            'users.mobile_number',
            'users.status',
            'users.role_id',
            'roles.name'
        )
            ->where('users.role_id', '=', 2)
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->get();

        return view('admin.users.edit')->with([
            'roles' => $roles,
            'user'  => $user,
            'states'  => $states,
            'Manager'  => $Manager,
            'TicketType'  => $TicketType,
        ]);
    }

    /**
     * Update User
     * @param Request $request, User $user
     * @return View Users
     * @author Shani Singh
     */
    public function update(Request $request, User $user)
    {
        // Validations
        $request->validate([
            'email' => 'required|unique:users,email,' . $user->id . ',id',
        ]);

        DB::beginTransaction();
        try {

            $ireportTo = 0;
            if ($request->role_id == 2) {
                $ireportTo  =  0;
            } else {
                $ireportTo = $request->ireportTo;
            }
            $user_updated = User::whereId($user->id)->update([
                'username'    => $request->username,
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'address1'     => $request->address1,
                'address2'     => $request->address2,
                'landmark'     => $request->landmark,
                'city'     => $request->city,
                'state'     => $request->state,
                'pincode'     => $request->pincode,
                'mobile_number' => $request->mobile_number,
                'secondarymobile' => $request->secondarymobile,
                'role_id'       => $request->role_id,
                'isInsurance'       => $request->isInsurance,
                'status'        => $request->status,
                'ireportTo'        => $ireportTo ?? 0,
                'itickettype'        => $request->itickettype ?? 0,
            ]);
            //dd($user_updated);

            // Delete Any Existing Role
            DB::table('model_has_roles')->where('model_id', $user->id)->delete();

            // Assign Role To User
            $user->assignRole($user->role_id);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('users.index')->with('success', 'User Updated Successfully.');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Delete User
     * @param User $user
     * @return Index Users
     * @author Shani Singh
     */
    public function delete(Request $request, User $user,)
    {
        // dd($request);
        // dd($user);
        DB::beginTransaction();
        try {
            // Delete User
            User::whereId($request->id)->delete();

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User Deleted Successfully!.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Import Users
     * @param Null
     * @return View File
     */
    public function importUsers()
    {
        return view('admin.users.import');
    }

    public function uploadUsers(Request $request)
    {
        Excel::import(new UsersImport, $request->file);

        return redirect()->route('users.index')->with('success', 'User Imported Successfully');
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }


    public function passwordupdate(Request $request)
    {
        // dd($user);
        //dd($request);
        $newpassword = ($request->newpassword);
        $confirmpassword = ($request->confirmpassword);

        if ($newpassword == $confirmpassword) {
            // dd('if');
            $Password = DB::table('users')
                ->where(['id' => $request->id])
                ->update([
                    'password' => Hash::make($request->confirmpassword),
                ]);
            // dd($Password);
            return redirect()->route('users.index')->with('success', 'User Password Updated Successfully.');
        } else {
            // dd('eles');
            return redirect()->route('users.index')->with('error', 'password and confirm password does not match');
        }
    }
}

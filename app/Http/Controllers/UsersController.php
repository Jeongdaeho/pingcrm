<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Schema\Blueprint;

class UsersController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'roles' => $this->roleList(),
            'users' => User::orderBy('id', 'desc')
                ->with('roles', function($query){
                    $query->select('id', 'name');
                })
                ->filter(Request::only('search', 'role', 'trashed'))
                ->paginate(20)
                ->withQueryString()
                ->through( function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'roles' => $user->roles->map(function ($role){
                            return $role->name;
                        }),
                        'created_at' => $user->created_at->toDateString(),
                        'email' => $user->email,
                        'deleted_at' => $user->deleted_at,
                    ];
                }),
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create', [
            'roles' => $this->roleList(),
        ]);
    }

    public function store()
    {
        $input = Request::validate([
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['required', 'string', 'min:8'],
            'roles' => ['nullable'],
        ]);

        DB::transaction(function () use ($input) {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
            ]);

            if( $this->roleList()->pluck('name')->contains( $input['roles']) )
            {
                $user->assignRole($input['roles']);
            }
        }, 1);
        return Redirect::route('users.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'deleted_at' => $user->deleted_at,
            ],
        ]);
    }

    public function update(User $user)
    {
        if (App::environment('demo') && $user->isDemoUser()) {
            return Redirect::back()->with('error', 'Updating the demo user is not allowed.');
        }

        Request::validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable'],
        ]);

        $user->update(Request::only('name', 'email'));

        if (Request::get('password')) {
            $user->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        if (App::environment('demo') && $user->isDemoUser()) {
            return Redirect::back()->with('error', 'Deleting the demo user is not allowed.');
        }

        $user->delete();

        return Redirect::back()->with('success', 'User deleted.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return Redirect::back()->with('success', 'User restored.');
    }

    //root 를 제외한 role 목록.
    public function roleList()
    {
        $roles = Role::select('id', 'name');
        if ( !Gate::allows('super admin') ) {
            Role::select('name')->where('name', '!=', 'root')->get();
        }

        return $roles->get();
    }
}

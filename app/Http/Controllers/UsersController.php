<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Users/Index', [
            'filters' => $request->all('search', 'role', 'trashed'),
            'roles' => $this->roleList(),
            'users' => User::orderBy('id', 'desc')
                ->with('roles', function($query){
                    $query->select('id', 'name');
                })
                ->filter($request->only('search', 'role', 'trashed'))
                ->paginate(20)
                ->withQueryString()
                ->through( function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'roles' => $user->roles,
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
            'role' => $this->roleList(),
        ]);
    }

    public function store(Request $request)
    {
        // $user->roles()->sync($request->input('roles.*.id', []));

        $input = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['nullable',  Rule::in($this->roleList()->pluck('id'))],
        ]);

        DB::transaction(function () use ($input) {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
            ]);

            $user->syncRoles($input['role']);
        }, 1);
        return Redirect::route('users.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'roles' => $this->roleList(),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'deleted_at' => $user->deleted_at,
                'role' => $user->roles->get(0)->id?? '',
            ],

        ]);
    }

    public function update(User $user, Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['nullable',  Rule::in($this->roleList()->pluck('id'))],
        ]);

        DB::transaction(function () use ($user, $request) {
            $user->update($request->only('name', 'email'));
            if ($request->get('password')) {
                $user->update(['password' => $request->get('password')]);
            }

            if ($request->get('role')) {
                $user->syncRoles($request->get('role'));
            }
        });

        return Redirect::back()->with('success', '수정 되었습니다.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return Redirect::back()->with('success', '삭제 되었습니다.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return Redirect::back()->with('success', '복구 되었습니다.');
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

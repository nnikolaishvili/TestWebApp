<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\StoreRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class UsersController extends Controller
{
    /**
     * View of the users
     *
     * @return View
     */
    public function index(): View
    {
        $users = User::with('role:id,name')->isNotAdmin()->paginate(User::ITEMS_PER_PAGE);

        return view('users.index', compact('users'));
    }

    /**
     * Create page of the user
     *
     * @return View
     */
    public function create(): View
    {
        $roles = Role::where('id', '!=', Role::ADMIN)->pluck('name', 'id')->toArray();

        return view('users.create', compact('roles'));
    }

    /**
     * Store user
     *
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['email_verified_at'] = now();
        User::create($validated);

        return redirect()->route('users.index');
    }

    /**
     * Edit view of the user
     *
     * @param User $user
     * @return View
     */
    public function edit(User $user): View
    {
        $roles = Role::where('id', '!=', Role::ADMIN)->pluck('name', 'id')->toArray();

        return view('users.edit', compact('roles', 'user'));
    }

    /**
     * Update user
     * update role_id only
     *
     * @param User $user
     * @param UpdateRequest $request
     * @return RedirectResponse
     */
    public function update(User $user, UpdateRequest $request): RedirectResponse
    {
        $user->update($request->validated());

        return redirect()->route('users.index');
    }
}

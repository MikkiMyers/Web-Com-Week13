<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
const array ROlES = ['ADMIN', 'USER'];

    protected string $title = 'Users';
    protected int $itemsPerPage = 10;

    protected function getQuery(): Builder
    {
        return User::orderBy('id');
    }

    protected function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    protected function getListViewName(): string
    {
        return 'users.list';
    }

    public function list(Request $request): View
    {
        $search = $request->query('term');
        $query = $this->getQuery()->when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('role', 'like', "%{$search}%");
        });

        $users = $query->paginate($this->getItemsPerPage());

        return view($this->getListViewName(), [
            'title' => 'User List',
            'search' => ['term' => $search],
            'users' => $users,
        ]);
    }


    public function show(string $email): View
    {
        $user = User::where('email', $email)->firstOrFail();
        return view('users.view', [
            'title' => 'User Details',
            'user' => $user,
        ]);
    }

    public function showCreateForm(): View
    {
        Gate::authorize('create', User::class); // Authorization check
        return view('users.create-form', [
            'title' => "{$this->title}: Create",
            'role' => self::ROlES,
        ]);
    }
    function create(ServerRequestInterface $request): RedirectResponse
{
    Gate::authorize('create', User::class);
    
    try {
        $data = $request->getParsedBody();
        $user = new User();
        $user->fill($data);
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->save();
       
        return redirect()->route('users.list')->with('status', "User {$user->name} was created.");
    } catch (QueryException $excp) {
        return redirect()->back()->withInput()->withErrors([
            'error' => $excp->errorInfo[2], // ข้อความข้อผิดพลาดจากฐานข้อมูล
        ]);
    }
}

    
    public function showEditForm(string $userId): View
    {
        $user = User::findOrFail($userId);
        Gate::authorize('update', $user); // Pass the $user instance

        return view('users.edit-form', [
            'user' => $user,
            'title' => "Edit User: " . $user->name,
        ]);
    }

    public function update(Request $request, string $userId): RedirectResponse
{
    $user = User::findOrFail($userId);
    Gate::authorize('update', $user);

    try {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:4',
        ]);

        $user->update(array_filter($validatedData, fn($value) => !is_null($value)));
        
        return redirect()->route('users.list')->with('status', "User {$user->name} was updated.");
    } catch (QueryException $excp) {
        return redirect()->back()->withInput()->withErrors([
            'error' => $excp->errorInfo[2], // ข้อความข้อผิดพลาดจากฐานข้อมูล
        ]);
    }
}


public function delete(string $userId): RedirectResponse
{
    $userToDelete = User::findOrFail($userId);
    Gate::authorize('delete', [$userToDelete]);

    try {
        $userToDelete->delete();
        
        return redirect()->route('users.list')->with('status', "User {$userToDelete->name} was deleted.");
    } catch (QueryException $excp) {
        return redirect()->back()->withErrors([
            'error' => $excp->errorInfo[2], // ข้อความข้อผิดพลาดจากฐานข้อมูล
        ]);
    }
}

    public function showSelf(): View
    {
        $user = Auth::user();
        return view('users.self', [
            'user' => $user,
            'title' => 'User Profile',
        ]);
    }

    public function showUpdateSelf(string $userId): View
    {
        $user = User::findOrFail($userId);
        Gate::authorize('update', $user);

        return view('users.update-self', [
            'user' => $user,
            'title' => "Edit Profile: " . $user->name,
        ]);
    }

    public function updateSelf(Request $request, string $id): RedirectResponse
{
    $user = User::findOrFail($id);
    Gate::authorize('update', $user);

    try {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('users.self', $user->id)->with('status', "Your profile has been updated.");
    } catch (\Illuminate\Database\QueryException $excp) {
        return redirect()->back()->withInput()->withErrors([
            'error' => $excp->errorInfo[2], // ข้อความข้อผิดพลาดจากฐานข้อมูล
        ]);
    }
}
}

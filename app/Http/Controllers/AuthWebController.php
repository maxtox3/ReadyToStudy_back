<?php

namespace App\Http\Controllers;

use App\Discipline;
use App\Group;
use App\Task;
use App\Test;
use App\Theme;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AuthWebController extends Controller
{

    public function login(Request $request)
    {
        if (!auth()->attempt(request(['email', 'password']))) {
            return back()->withErrors([
                'message' => 'Пожалуйста, проверьте введенные вами данные'
            ]);
        }
        session()->put('user', auth()->user());
        session()->flash('message', 'Добро пожаловать, ' . auth()->user()->name . '!');
        return redirect()->home();
    }

    public function getRegister()
    {
        $groups = Group::all();
        return view('register', compact('groups'));
    }

    public function register(Request $request)
    {
        $this->validate(request(), [
            'group_name' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        if (User::where('email', '=', $request['email'])->first() != null) {
            session()->flash('error', 'Пользователь с таким email уже существует');
            return redirect()->back();
        }

        $group = Group::where('name', '=', $request->group_name)->first();

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->group_id = $group->id;

        if ($user->save()) {
            session()->flash('message', 'Регистрация прошла успешно! Пожалуйста, пройдите авторизацию');
            return redirect('login');
        }

        session()->flash('error', 'Произошла ошибка');
        return redirect()->back();
    }

    public function logout()
    {
        session()->put('user', null);

        return redirect()->home();
    }

    public function getProfile()
    {
        if (session()->get('user') == null) {
            session()->flash('error', 'Доступ возможен только авторизованным пользователям');
            return redirect('/login');
        }

        $user = session()->get('user');

        $group = Group::find($user->group_id)->first();
        $groups = Group::all();
        return view('profile', compact(['group', 'groups']));
    }
}
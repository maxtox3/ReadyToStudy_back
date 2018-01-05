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

class WebController extends Controller
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

    public function getRegister(){
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

        $group = Group::where('name','=',$request->group_name)->first();

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->group_id = $group->id;

        if($user->save()){
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

    public function getImport()
    {
        if (session()->get('user') == null) {
            session()->flash('error', 'Доступ возможен только авторизованным пользователям');
            return redirect('/login');
        }
        $user = session()->get('user');
        if (!$user->is_teacher) {
            session()->flash('error', 'У вас нет прав');
            return redirect('/');
        }

        $disciplines = Discipline::where('teacher_id', '=', $user->id)->get();

        return view('import', compact(['disciplines']));
    }

    public function import(Request $request)
    {
        $this->validate(request(), [
            'theme_name' => 'required',
            'discipline_name' => 'required',
            'testName' => 'required',
            'import_file' => 'required'
        ]);

        if (session()->get('user') == null) {
            session()->flash('error', 'Доступ возможен только авторизованным пользователям');
            return redirect('/login');
        }
        $user = session()->get('user');
        if (!$user->is_teacher) {
            session()->flash('error', 'У вас нет прав');
            return redirect('/');
        }

        $theme = Theme::where('name', '=', $request->theme_name)->first();
        $themeId = $theme->id;

        $existingTest = Test::where('name', '=', $request->testName);
        if ($existingTest != null) {
            session()->flash('error', 'Тест с таким названием уже существует');
            return redirect()->back();
        }

        $test = new Test();
        $test->name = $request->testName;
        $test->theme_id = $themeId;

        if ($test->save()) {
            $test = Test::where('name', '=', $test->name)->first();

            $path = $request->file('import_file')->getRealPath();
            $data = Excel::load($path, function ($reader) {
            })->get();
            if (!empty($data) && $data->count()) {
                foreach ($data->toArray() as $key => $value) {
                    $task = new Task();
                    $task->test_id = $test->id;
                    $task->name = $value['name'];
                    $task->first_var = $value['first_var'];
                    $task->second_var = $value['second_var'];
                    $task->third_var = $value['third_var'];
                    $task->fourth_var = $value['fourth_var'];
                    $task->right_var = $value['right_var'];
                    if (!$task->save()) {
                        session()->flash('error', 'Ошибка! Проверьте ваш файл, или обратитесь к администратору');
                        return back();
                    }
                }
                session()->flash('message', 'Импорт прошел успешно');
                return redirect('/');
            }
        }
        session()->flash('error', 'Ошибка! Проверьте ваш файл, или обратитесь к администратору');
        return back();
    }

    public function themesByName(Request $request)
    {
        if ($request->ajax()) {
            $discipline = Discipline::where('name', '=', $request->disciplineName)->get();
            $themes = $discipline[0]->theme()->get();
            $output = '';
            foreach ($themes as $theme) {
                $output .= '<option>' . $theme->name . '</option>';
            }

            return Response($output);
        }
    }

    public function getCreateDiscipline()
    {
        if (session()->get('user') == null) {
            session()->flash('error', 'Доступ возможен только авторизованным пользователям');
            return redirect('/login');
        }
        $user = session()->get('user');
        if (!$user->is_teacher) {
            session()->flash('error', 'У вас нет прав');
            return redirect('/');
        }

        $disciplines = Discipline::where('teacher_id', '=', $user->id)->get();
        $groupes = array();
        foreach ($disciplines as $discipline) {
            foreach ($discipline->group()->get() as $group) {
                array_push($groupes, $group->name);
            }
        }

        $groups = array_unique($groupes);
        $type = 'discipline';

        return view('create', compact(['type', 'groups']));
    }

    public function createDiscipline(Request $request)
    {
        $this->validate(request(), [
            'group_name' => 'required',
            'name' => 'required',
        ]);

        $user = session()->get('user');
        if (!$user->is_teacher) {
            session()->flash('error', 'У вас нет прав');
            return redirect('/');
        }

        $existingDiscipline = Discipline::where('name', '=', $request->name)->first();
        if ($existingDiscipline != null) {
            session()->flash('error', 'Ошибка! Дисциплина с таким названием уже существует');
            return back();
        }

        $group = Group::where('name', '=', $request->group_name)->first();

        $discipline = new Discipline();
        $discipline->teacher_id = $user->id;
        $discipline->name = $request->name;
        if ($discipline->save()) {
            $discipline->group()->attach($group->id);
            session()->flash('message', "Дисциплина " . $discipline->name . " успешно создана");
            return redirect('/');
        }
        session()->flash('error', 'Ошибка! не удалось создать дисциплину');
        return back();
    }

    public function getCreateTheme()
    {
        if (session()->get('user') == null) {
            session()->flash('error', 'Доступ возможен только авторизованным пользователям');
            return redirect('/login');
        }
        $user = session()->get('user');
        if (!$user->is_teacher) {
            session()->flash('error', 'У вас нет прав');
            return redirect('/');
        }

        $type = 'theme';
        $disciplines = Discipline::where('teacher_id', '=', $user->id)->get();
        return view('create', compact(['type', 'disciplines']));
    }

    public function createTheme(Request $request)
    {
        $this->validate(request(), [
            'discipline_name' => 'required',
            'name' => 'required',
        ]);

        if (session()->get('user') == null) {
            session()->flash('error', 'Доступ возможен только авторизованным пользователям');
            return redirect('/login');
        }
        $user = session()->get('user');
        if (!$user->is_teacher) {
            session()->flash('error', 'У вас нет прав');
            return redirect('/');
        }

        $existingTheme = Theme::where('name', '=', $request->name)->first();
        if ($existingTheme != null) {
            session()->flash('error', 'Ошибка! Тема с таким названием уже существует');
            return back();
        }

        $discipline = Discipline::where('name', '=', $request->discipline_name)->first();

        $theme = new Theme();
        $theme->name = $request->name;
        $theme->discipline_id = $discipline->id;

        if ($theme->save()) {
            session()->flash('message', "Тема " . $theme->name . " успешно создана");
            return redirect('/');
        }
        session()->flash('error', 'Ошибка! не удалось создать тему');
        return back();
    }

    public function getImportGroups()
    {
        if (session()->get('user') == null) {
            session()->flash('error', 'Доступ возможен только авторизованным пользователям');
            return redirect('/login');
        }
        $user = session()->get('user');
        if (!$user->is_teacher) {
            session()->flash('error', 'У вас нет прав');
            return redirect('/');
        }

        return view('importGroups');
    }

    public function importGroups(Request $request)
    {
        $this->validate(request(), [
            'import_file' => 'required'
        ]);

        if (session()->get('user') == null) {
            session()->flash('error', 'Доступ возможен только авторизованным пользователям');
            return redirect('/login');
        }
        $user = session()->get('user');
        if (!$user->is_teacher) {
            session()->flash('error', 'У вас нет прав');
            return redirect('/');
        }

        $path = $request->file('import_file')->getRealPath();
        $data = Excel::load($path, function ($reader) {
        })->get();
        if (!empty($data) && $data->count()) {
            foreach ($data->toArray() as $key => $value) {

                $existingGroup = Group::where('name', '=', $value['name'])->first();
                if ($existingGroup != null) {
                    continue;
                }

                $group = new Group();
                $group->name = $value['name'];
                if (!$group->save()) {
                    session()->flash('error', 'Ошибка! Проверьте ваш файл, или обратитесь к администратору');
                    return back();
                }
            }
            session()->flash('message', 'Импорт прошел успешно');
            return redirect('/');
        }
        session()->flash('error', 'Ошибка! Проверьте ваш файл, или обратитесь к администратору');
        return back();
    }
}

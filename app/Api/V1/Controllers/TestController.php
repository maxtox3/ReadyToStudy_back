<?php

namespace App\Api\V1\Controllers;

use App\FinishedTest;
use App\Http\Controllers\Controller;
use App\Test;
use App\Theme;
use Dingo\Api\Exception\ValidationHttpException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller
{
    use Helpers;

    public function all(Request $request)
    {
        if ($request->query('theme_id') != null) {
            $theme = Theme::find($request->query('theme_id'));
            $tests = $theme->test()->get();

            return response()
                ->json([
                    'status' => 'ok',
                    'theme' => $theme,
                    'tests' => $tests
                ]);
        } else {
            $this->response->errorNotFound();
        }
        return null;
    }

    public function get($id)
    {
        $test = Test::find($id);
        $tasks = $test->task()->get();

        return response()
            ->json([
                'status' => 'ok',
                'test' => $test,
                'tasks' => $tasks
            ]);

    }

    public function finish(Request $request)
    {
        $user = Auth::user();

        $credentials = $request->only([
            'test_id',
            'right_answers_count',
            'bad_answers_count',
            'time_spent',
        ]);

        $validator = Validator::make($credentials, [
            'test_id' => 'required',
            'right_answers_count' => 'required',
            'bad_answers_count' => 'required',
            'time_spent' => 'required',
        ]);
        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        $existingTest = DB::table('finished_tests')
            ->whereRaw('test_id = ? and user_id = ?', [$credentials['test_id'], $user->id])
            ->get();

        if ($existingTest->count() == 0) {
            $finishedTest = new FinishedTest();
            $finishedTest->user_id = $user->id;
            $finishedTest->test_id = $credentials['test_id'];
            $finishedTest->right_answers_count = $credentials['right_answers_count'];
            $finishedTest->bad_answers_count = $credentials['bad_answers_count'];
            $finishedTest->time_spent = $credentials['time_spent'];

            if ($finishedTest->save()) {
                return response()
                    ->json([
                        'status' => 'ok'
                    ]);
            } else {
                return $this->response->error('Could not create finished_test', 500);
            }
        } else {
            $existingTest = FinishedTest::whereRaw('test_id = ? and user_id = ?', [$credentials['test_id'], $user->id])->first();
            $existingTest->fill($credentials);
            if ($existingTest->save()) {
                return response()
                    ->json([
                        'status' => 'ok'
                    ]);
            } else {
                return $this->response->error('Could not update finished_test', 500);
            }
        }

    }

    public function create(Request $request)
    {
    }

    public function update($id, Request $request)
    {
    }

    public function delete()
    {

    }
}

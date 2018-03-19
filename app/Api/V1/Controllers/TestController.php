<?php

namespace App\Api\V1\Controllers;

use App\FinishedTask;
use App\Http\Controllers\Controller;
use App\Test;
use App\Theme;
use Dingo\Api\Exception\ValidationHttpException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Auth;
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

        $finishedTask = new FinishedTask();
        $finishedTask->user_id = $user->id;
        $finishedTask->test_id = $credentials['test_id'];
        $finishedTask->right_answers_count = $credentials['right_answers_count'];
        $finishedTask->bad_answers_count = $credentials['bad_answers_count'];
        $finishedTask->time_spent = $credentials['time_spent'];

        if ($finishedTask->save()) {
            return response()
                ->json([
                    'status' => 'ok'
                ]);
        } else {
            return $this->response->error('Could not create finished_task', 500);
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

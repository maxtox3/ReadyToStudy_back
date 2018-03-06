<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Гусев Максим Александрович',
            'email' => 'kellia-16@yandex.ru',
            'password' => '$2y$10$ETVCsSabCWqZBjH.PO4G0eqZZiXolZKPwdEVhOMjyf/o.4dSCyrjO',
            'is_teacher' => '0',
            'group_id' => '1',
        ]);

        DB::table('users')->insert([
            'name' => 'Махин Семен Владимирович',
            'email' => 'makhin@yandex.ru',
            'password' => '$2y$10$9203htw0p1GchhN2477.JOoU6Y7NjFSlybvOdHO7DYFIAWkxN2the',
            'is_teacher' => '0',
            'group_id' => '2',
        ]);

        DB::table('users')->insert([
            'name' => 'Чернышов Лев Николавеич',
            'email' => 'levchern@gmail.com',
            'password' => '$2y$10$fYi2Pqrgg3xDIciqYXJaiOnU1XmgFPN.I.CHDc61mOXkgGIzizyv2',
            'is_teacher' => '1',
            'group_id' => '0',
        ]);

        DB::table('users')->insert([
            'name' => 'Аза Давидовна',
            'email' => 'aza@mail.com',
            'password' => '$2y$10$ci2KQpyR6YJEZjxw1whcI.yDxIbVpNNwCe6I2HngI1RwEcqSz40Fi',
            'is_teacher' => '1',
            'group_id' => '0',
        ]);

        DB::table('users')->insert([
            'name' => 'Великий Математик',
            'email' => 'math@mail.com',
            'password' => '$2y$10$dSKwn5HwQ2KcFHIoi20fzeH4uk4OlyuB3Loz.nA1bpOMkUzycJVRa',
            'is_teacher' => '1',
            'group_id' => '0',
        ]);

        DB::table('groups')->insert([
            'name' => 'PI4-2'
        ]);

        DB::table('groups')->insert([
            'name' => 'PI4-1'
        ]);

        DB::table('groups')->insert([
            'name' => 'PI3-2'
        ]);

        DB::table('groups')->insert([
            'name' => 'PI3-1'
        ]);

        DB::table('groups')->insert([
            'name' => 'PI2-2'
        ]);

        DB::table('groups')->insert([
            'name' => 'PI2-1'
        ]);

        DB::table('groups')->insert([
            'name' => 'PI1-2'
        ]);

        DB::table('groups')->insert([
            'name' => 'PI1-1'
        ]);

        DB::table('disciplines')->insert([
            'teacher_id' => '3',
            'name' => 'Web-программирование',
        ]);

        DB::table('disciplines')->insert([
            'teacher_id' => '3',
            'name' => 'Операционные системы',
        ]);

        DB::table('disciplines')->insert([
            'teacher_id' => '4',
            'name' => 'Философия',
        ]);

        DB::table('disciplines')->insert([
            'teacher_id' => '5',
            'name' => 'Математический анализ',
        ]);

        DB::table('discipline_group')->insert([
            'group_id' => 1,
            'discipline_id' => 2
        ]);

        DB::table('discipline_group')->insert([
            'group_id' => 1,
            'discipline_id' => 1
        ]);

        DB::table('discipline_group')->insert([
            'group_id' => 2,
            'discipline_id' => 2
        ]);

        DB::table('discipline_group')->insert([
            'group_id' => 3,
            'discipline_id' => 1
        ]);

        DB::table('discipline_group')->insert([
            'group_id' => 4,
            'discipline_id' => 2
        ]);

        DB::table('discipline_group')->insert([
            'group_id' => 4,
            'discipline_id' => 1
        ]);

        DB::table('discipline_group')->insert([
            'group_id' => 4,
            'discipline_id' => 4
        ]);

        DB::table('discipline_group')->insert([
            'group_id' => 3,
            'discipline_id' => 3
        ]);

        DB::table('themes')->insert([
            'name' => 'Windows',
            'discipline_id' => '2',
        ]);

        DB::table('themes')->insert([
            'name' => 'Linux',
            'discipline_id' => '2',
        ]);

        DB::table('themes')->insert([
            'name' => 'Angular',
            'discipline_id' => '1',
        ]);

        DB::table('themes')->insert([
            'name' => 'React',
            'discipline_id' => '1',
        ]);

        DB::table('themes')->insert([
            'name' => 'JQuery',
            'discipline_id' => '1',
        ]);

        DB::table('tests')->insert([
            'name' => 'Тест по теме Angular',
            'description' => 'тут будет очень большое описание, которое описывает описание, тут будет очень большое описание, которое описывает описание, тут будет очень большое описание, которое описывает описание, тут будет очень большое описание, которое описывает описание,',
            'theme_id' => '3',
        ]);

        DB::table('tests')->insert([
            'name' => 'Тест по теме React',
            'description' => 'тут будет очень большое описание, которое описывает описание, тут будет очень большое описание, которое описывает описание, тут будет очень большое описание, которое описывает описание, тут будет очень большое описание, которое описывает описание,',
            'theme_id' => '4',
        ]);

        DB::table('tests')->insert([
            'name' => 'Тест по теме Windows',
            'description' => 'тут будет очень большое описание, которое описывает описание, тут будет очень большое описание, которое описывает описание, тут будет очень большое описание, которое описывает описание, тут будет очень большое описание, которое описывает описание,',
            'theme_id' => '1',
        ]);

        DB::table('tests')->insert([
            'name' => 'Тест по теме Linux',
            'description' => 'тут будет очень большое описание, которое описывает описание, тут будет очень большое описание, которое описывает описание, тут будет очень большое описание, которое описывает описание, тут будет очень большое описание, которое описывает описание,',
            'theme_id' => '2',
        ]);

        DB::table('tasks')->insert([
            'test_id' => '1',
            'name' => 'Что такое Angular?',
            'first_var' => 'first variant',
            'second_var' => 'second variant',
            'third_var' => 'third variant',
            'fourth_var' => 'fourth variant',
            'right_var' => 1,
        ]);

        DB::table('tasks')->insert([
            'test_id' => '1',
            'name' => 'зачем нужен Angular?',
            'first_var' => 'first variant',
            'second_var' => 'second variant',
            'third_var' => 'third variant',
            'fourth_var' => 'fourth variant',
            'right_var' => 2,
        ]);

        DB::table('tasks')->insert([
            'test_id' => '1',
            'name' => 'а он точно нужен, этот Angular?',
            'first_var' => 'first variant',
            'second_var' => 'second variant',
            'third_var' => 'third variant',
            'fourth_var' => 'fourth variant',
            'right_var' => 3,
        ]);

        DB::table('tasks')->insert([
            'test_id' => '1',
            'name' => 'не, ну вы точно уверены что нельзя написать все тоже самое с помощью jquery?',
            'first_var' => 'first variant',
            'second_var' => 'second variant',
            'third_var' => 'third variant',
            'fourth_var' => 'fourth variant',
            'right_var' => 4,
        ]);
    }
}

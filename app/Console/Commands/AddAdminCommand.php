<?php

namespace App\Console\Commands;

use App\Constant\Roles;
use App\Http\Requests\Rules\UserRules;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AddAdminCommand extends Command
{
    public function __construct(
        private UserRules $userRules,
    ) {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds God user to the system';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->askForInput('Please state you email', 'email');
        $password = $this->askForInput('Please state your password', 'password', 'secret');
        $firstName = $this->askForInput('Please state your first name', 'first_name');
        $lastName = $this->askForInput('Please state your last name', 'last_name');
        $dob = $this->askForInput('Please state your date of birth (format Y-m-d)', 'dob');
        $gender = $this->askForInput('Please state your gender', 'gender');
        $phone = $this->askForInput('Please state your phone', 'phone');

        $this->createUser($email, $password, $firstName, $lastName, $dob, $gender, $phone);

        $this->newLine(2);
        $this->line('Admin successfully created');

        return 0;
    }

    private function askForInput(string $question, string $field, string $method = 'ask')
    {
        $answer = '';

        $valid = false;
        while($valid === false) {
            $answer = $this->$method($question);

            $valid = $this->validateAnswer($field, $answer);
        }

        return $answer;
    }

    private function validateAnswer(string $field, string $answer): bool
    {
        $validator = Validator::make([$field => $answer], [$field => $this->userRules->get($field)]);

        if($validator->fails()) {
            $this->error($validator->errors()->first());

            return false;
        }

        return true;
    }

    private function createUser(
        string $email,
        string $password,
        string $firstName,
        string $lastName,
        string $dob,
        string $gender,
        string $phone
    ): void {
        $user = new User();
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->gender = $gender;
        $user->dob = $dob;
        $user->phone = $phone;
        $user->roles = [Roles::ADMIN];
        $user->active = true;
        $user->phone_verified = true;

        $user->save();
    }
}

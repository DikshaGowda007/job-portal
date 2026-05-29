<?php

namespace App\Console\Commands;

use Http;
use Illuminate\Console\Command;

class HitLoginApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:hit-login-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hit login api with multiple user roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $responses = [];
        $payloads = [
            [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'janedoer@example.com',
                'password' => 'password123',
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'janedoer@example.com',
                'password' => 'password123',
                'role' => 'JOB_SEEKER',
            ],
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'jobseeker@example.com',
                'password' => 'password123',
                'role' => 'JOB_SEEKER',
            ],
            [
                'first_name' => 'Riya',
                'last_name' => 'Sharma',
                'email' => 'diksha@depasserinfotech.in',
                'password' => 'recruiter123',
                'role' => 'RECRUITER',
            ],
            [
                'first_name' => 'Subadmin',
                'last_name' => 'subadmin',
                'email' => 'subadmin@123',
                'password' => 'subadmin123',
            ],
            [
                'first_name' => 'Admin',
                'last_name' => 'admin',
                'email' => 'admin@123',
                'password' => 'admin123',
            ],
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'diksha@depasserinfotech.in',
                'password' => 'password123',
            ],
        ];

        foreach ($payloads as $key => $value) {
            $request = Http::post('http://localhost:8080/api/v1/auth/login', $value);
            // dd($request);

            $responses[] = [
                'index' => $key,
                'request' => $value,
                'response' => $request->json()['data']['request_token'] ?? $request->body(),
            ];
        }

        file_put_contents(storage_path('logs/login_response.json'), json_encode($responses, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

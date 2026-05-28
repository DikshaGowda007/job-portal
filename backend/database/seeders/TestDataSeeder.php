<?php

namespace Database\Seeders;

use App\Constants\UserConstant;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run: php artisan db:seed --class=TestDataSeeder
     *
     * Creates test users, jobs, and applications
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Create Recruiter
        $recruiterId = DB::table('users')->insertGetId([
            'first_name' => 'Test',
            'last_name' => 'Recruiter',
            'email' => 'recruiter@test.com',
            'password' => Hash::make('password123'), // Update hash method if needed
            'user_type' => UserConstant::USER_ROLE_RECRUITER,
            'verified' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Create Job Seekers
        $seekerIds = [];
        for ($i = 1; $i <= 3; $i++) {
            $seekerIds[] = DB::table('users')->insertGetId([
                'first_name' => 'Seeker',
                'last_name' => "User$i",
                'email' => "seeker$i@test.com",
                'password' => Hash::make('password123'),
                'user_type' => UserConstant::USER_ROLE_JOB_SEEKER,
                'verified' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Create Jobs
        $jobs = [
            [
                'user_id' => $recruiterId,
                'company_name' => 'Tech Corp',
                'title' => 'Senior PHP Developer',
                'job_description' => 'Looking for experienced PHP developer.',
                'location' => 'Remote',
                'salary_min' => 80000,
                'salary_max' => 120000,
                'salary_currency' => 'USD',
                'salary_type' => 'yearly',
                'work_mode' => 'remote',
                'job_type' => 'FULL_TIME',
                'experience_level' => 'SENIOR',
                'experience_min' => 5,
                'experience_max' => 10,
                'skills' => json_encode(['PHP', 'Laravel', 'MySQL']),
                'status' => 'OPEN',
                'openings_count' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => $recruiterId,
                'company_name' => 'Startup Inc',
                'title' => 'Junior Frontend Developer',
                'job_description' => 'Great opportunity for freshers.',
                'location' => 'New York',
                'salary_min' => 40000,
                'salary_max' => 60000,
                'salary_currency' => 'USD',
                'salary_type' => 'yearly',
                'work_mode' => 'hybrid',
                'job_type' => 'FULL_TIME',
                'experience_level' => 'FRESHER',
                'experience_min' => 0,
                'experience_max' => 2,
                'skills' => json_encode(['JavaScript', 'React', 'CSS']),
                'status' => 'OPEN',
                'openings_count' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => $recruiterId,
                'company_name' => 'Global Solutions',
                'title' => 'DevOps Engineer',
                'job_description' => 'AWS and Docker experience required.',
                'location' => 'San Francisco',
                'salary_min' => 100000,
                'salary_max' => 150000,
                'salary_currency' => 'USD',
                'salary_type' => 'yearly',
                'work_mode' => 'onsite',
                'job_type' => 'FULL_TIME',
                'experience_level' => 'MID',
                'experience_min' => 3,
                'experience_max' => 6,
                'skills' => json_encode(['AWS', 'Docker', 'Kubernetes', 'CI/CD']),
                'status' => 'OPEN',
                'openings_count' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $jobIds = [];
        foreach ($jobs as $job) {
            $jobIds[] = DB::table('job_posts')->insertGetId($job);
        }

        // Create Job Applications
        foreach ($seekerIds as $index => $seekerId) {
            // Each seeker applies to 2 jobs
            $jobsToApply = array_slice($jobIds, 0, 2);
            foreach ($jobsToApply as $jobId) {
                DB::table('job_applications')->insert([
                    'job_post_id' => $jobId,
                    'user_id' => $seekerId,
                    'cover_letter' => 'I am interested in this position.',
                    'expected_salary' => 70000 + ($index * 5000),
                    'expected_salary_currency' => 'USD',
                    'notice_period' => '2 weeks',
                    'experience_years' => $index + 1,
                    'status' => 'PENDING',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $this->command->info('Test data created successfully!');
        $this->command->info('');
        $this->command->info('Users created:');
        $this->command->info('  Recruiter: recruiter@test.com / password123');
        $this->command->info('  Seekers: seeker1@test.com, seeker2@test.com, seeker3@test.com / password123');
        $this->command->info('');
        $this->command->info('Jobs created: '.count($jobIds));
        $this->command->info('Applications created: '.(count($seekerIds) * 2));
    }
}

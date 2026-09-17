<?php

namespace App\Modules\V1\Job\Services\Analyze;

use App\Services\AI\GeminiClient;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Throwable;

class DetailsService
{
    private const INSTRUCTION = 'You are reviewing a job posting draft before it is published. Do three things: '
        .'(1) Extract structured hiring requirements — separate technical/tooling skills from soft skills, and '
        .'only mark a skill as required if the text explicitly states it is required/mandatory, otherwise treat '
        .'it as preferred. '
        .'(2) Review the text itself and flag real problems a recruiter should fix: missing information '
        .'(e.g. no salary range, no clear requirements), exclusionary or biased language (e.g. age-coded phrasing '
        .'like "young and energetic", unnecessary language/nationality requirements, gendered wording), or '
        .'requirements too vague to screen candidates against. Only flag genuine issues — return an empty list '
        .'if the text has none. Never flag or comment on protected characteristics of a candidate; only flag '
        .'language in the posting itself. '
        .'(3) Only if the text explicitly states a salary figure or range, extract it as salary_min/salary_max '
        .'(plain numbers, no currency symbols or separators) plus its currency and pay period; and only if the '
        .'text explicitly states an education requirement, extract it. Never guess or invent a salary or '
        .'education requirement that is not actually stated — leave those fields null if absent. '
        .'(4) If the text describes day-to-day duties or responsibilities, extract them as a short list of '
        .'concise bullet points (rewritten cleanly, not copied verbatim) — return an empty list if the text '
        .'has no clear responsibilities described.';

    private const SCHEMA = [
        'type' => 'OBJECT',
        'properties' => [
            'required_skills' => ['type' => 'ARRAY', 'items' => ['type' => 'STRING']],
            'preferred_skills' => ['type' => 'ARRAY', 'items' => ['type' => 'STRING']],
            'seniority_level' => ['type' => 'STRING', 'enum' => ['Junior', 'Mid', 'Senior', 'Lead']],
            'minimum_years_experience' => ['type' => 'INTEGER'],
            'salary_min' => ['type' => 'INTEGER', 'nullable' => true],
            'salary_max' => ['type' => 'INTEGER', 'nullable' => true],
            'salary_currency' => ['type' => 'STRING', 'enum' => ['INR', 'USD'], 'nullable' => true],
            'salary_type' => ['type' => 'STRING', 'enum' => ['monthly', 'yearly'], 'nullable' => true],
            'education' => ['type' => 'STRING', 'nullable' => true],
            'roles_responsibility' => ['type' => 'ARRAY', 'items' => ['type' => 'STRING']],
            'issues' => [
                'type' => 'ARRAY',
                'items' => [
                    'type' => 'OBJECT',
                    'properties' => [
                        'category' => ['type' => 'STRING', 'enum' => ['missing_info', 'bias', 'clarity']],
                        'severity' => ['type' => 'STRING', 'enum' => ['low', 'medium', 'high']],
                        'message' => ['type' => 'STRING'],
                    ],
                    'required' => ['category', 'severity', 'message'],
                ],
            ],
        ],
        'required' => [
            'required_skills', 'preferred_skills', 'seniority_level', 'minimum_years_experience',
            'salary_min', 'salary_max', 'salary_currency', 'salary_type', 'education',
            'roles_responsibility', 'issues',
        ],
    ];

    public function __construct(
        private GeminiClient $geminiClient
    ) {}

    public function analyze(string $jobDescription): JsonResponse
    {
        try {
            $analysis = $this->geminiClient->extractStructured(
                self::INSTRUCTION,
                $jobDescription,
                self::SCHEMA
            );

            return response()->json(CommonUtils::successDataResponse($analysis));
        } catch (Throwable $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        }
    }
}

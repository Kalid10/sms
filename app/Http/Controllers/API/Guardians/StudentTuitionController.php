<?php

namespace App\Http\Controllers\API\Guardians;

use App\Http\Requests\API\Guardians\StudentTuitionRequest;
use App\Http\Resources\Guardians\StudentTuition\Collection;
use App\Http\Resources\Guardians\StudentTuition\Resource;
use App\Models\Guardian;
use App\Models\StudentTuition;
use App\Services\StudentFeeService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StudentTuitionController extends Controller
{
    public function index(StudentTuitionRequest $request, ?StudentTuition $studentTuition)
    {
        $this->checkStudentsTuition(Auth::user()->load('guardian')->guardian);

        if ($studentTuition->exists) {
            return new Resource($studentTuition->load([
                'fee.feeable.schoolYear',
                'penalty',
                'student.user',
            ]));
        }

        $studentsTuition = auth()->user()
            ->load([
                'guardian.studentsTuition.fee.feeable.schoolYear',
                'guardian.studentsTuition.penalty',
                'guardian.studentsTuition.student.user',
            ])
            ->guardian->studentsTuition
            ->when($request->has('status'), function ($query) use ($request) {
                return $query->where('status', $request->input('status'));
            });

        return new Collection($studentsTuition);
    }

    public function processPayment(StudentTuitionRequest $request, StudentTuition $studentTuition)
    {
        $studentTuition = $studentTuition->load([
            'student.guardian.user',
            'fee.feeable.schoolYear',
        ]);

        $reference = 'tuition-'.now()->timestamp.'-'.$studentTuition->id;

        Log::info(json_encode([
            'amount' => $studentTuition->amount,
            'currency' => 'ETB',
            'email' => $studentTuition->student->guardian->user->email,
            'first_name' => explode(' ', $studentTuition->student->guardian->user->name)[0],
            'last_name' => explode(' ', $studentTuition->student->guardian->user->name)[1],
            'phone_number' => $studentTuition->student->guardian->user->phone_number,
            'tx_ref' => $reference,
        ]));

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.env('CHAPA_PUBLIC_TEST_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.chapa.co/v1/transaction/initialize', [
                'amount' => $studentTuition->amount,
                'currency' => 'ETB',
                'email' => 'adilabdu68@gmail.com',
                'first_name' => explode(' ', $studentTuition->student->guardian->user->name)[0],
                'last_name' => explode(' ', $studentTuition->student->guardian->user->name)[1],
                'phone_number' => ltrim($studentTuition->student->guardian->user->phone_number, '+'),
                'tx_ref' => $reference,
                'callback_url' => env('APP_URL')."/api/guardian/tuition/{$studentTuition['id']}/webhook",
                'return_url' => env('REDIRECT_URL'),
            ]);

            Log::info($response->json());

            return $response->json();
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function webhook()
    {
        Log::info('Webhook received');
    }

    private function checkStudentsTuition(Guardian $guardian)
    {
        $studentFeeService = new StudentFeeService();
        $studentsTuition = $guardian->load('studentsTuition.fee')->studentsTuition;
        $studentsTuition->each(function ($studentTuition) use ($studentFeeService) {
            $studentFeeService->prepareTuitionPayment($studentTuition);
        });
    }
}

<?php

namespace App\Http\Controllers\API\Guardians;

use App\Http\Requests\API\Students\UpdateRequest;
use App\Http\Resources\Guardians\Student\Resource;
use App\Models\Address;
use App\Models\Student;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;

class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function update(UpdateRequest $request, ?Student $student): \App\Http\Resources\Guardians\Guardian\Resource|Resource
    {

        try {
            DB::beginTransaction();

            $user = $student->exists ? $student->user : auth()->user();

            $user->update($request->only([
                'name', 'email', 'date_of_birth', 'phone_number', 'gender',
            ]));

            $addressData = $request->only(['sub_city', 'woreda', 'house_number']);

            if ($user->address) {
                $user->address->update($addressData);
            } else {
                $address = Address::create($addressData + ['city' => 'Addis Ababa', 'country' => 'Ethiopia']);

                $user->address()->associate($address);
                $user->save();
            }

            DB::commit();
            if ($student->exists) {
                return new Resource($student);
            } else {
                return new \App\Http\Resources\Guardians\Guardian\Resource($user->load('address'));
            }

        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }
    }
}

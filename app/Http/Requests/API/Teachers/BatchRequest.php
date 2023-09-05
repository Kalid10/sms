<?php

namespace App\Http\Requests\API\Teachers;

class BatchRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $teacher = $this->teacher();

        if ($this->route('batch')) {

            $batches = $teacher->load([
                'batchSubjects',
            ])->batchSubjects->pluck('batch_id');

            return $batches->contains($this->route('batch')->id);
        }

        return parent::authorize();
    }
}

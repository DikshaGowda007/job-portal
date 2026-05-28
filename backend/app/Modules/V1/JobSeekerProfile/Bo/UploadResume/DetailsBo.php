<?php

namespace App\Modules\V1\JobSeekerProfile\Bo\UploadResume;

use Illuminate\Http\UploadedFile;

class DetailsBo
{
    private UploadedFile $uploadedFile;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->uploadedFile)) {
            $collection['uploaded_file'] = $this->uploadedFile;
        }

        return $collection;
    }

    /**
     * Get the value of uploadedFile
     */
    public function getUploadedFile(): UploadedFile
    {
        return $this->uploadedFile;
    }

    /**
     * Set the value of uploadedFile
     */
    public function setUploadedFile(UploadedFile $uploadedFile): self
    {
        $this->uploadedFile = $uploadedFile;

        return $this;
    }
}

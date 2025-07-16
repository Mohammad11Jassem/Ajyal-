<?php

namespace App\Services;

use App\Models\Advertisement;
use App\Models\Course;
use App\Models\Image;
use App\Models\Teacher;
use Exception;
use Illuminate\Support\Facades\File;

class AdvertisementService
{

    public function createAdvertisement(array $data): array
    {
        try{

        // Create the advertisement
        $advertisement=Advertisement::create([
                                'title' => $data['title'],
                                'body' => $data['body'],
                                'advertisable_id' => $data['advertisable_id'],
                                'advertisable_type' => $data['advertisable_type'],
                    ]);

        // Handle file upload if image exists
        if (isset($data['images']) && is_array($data['images'])) {
            foreach ($data['images'] as $imageFile) {
                    if ($imageFile->isValid()) {
                        // $imagePath = $imageFile->store('advertisements', 'public');
                        $image = $advertisement->images()->create([
                            'path' => '' // Temporary, will be updated after saving the file
                        ]);
                        $imageName = time().$image->id. '.' . $imageFile->getClientOriginalExtension();
                        $imageFile->move(public_path('advertisements'), $imageName);
                        $imagePath = 'advertisements/' . $imageName;


                        $image->path=$imagePath;
                        $image->save();
                    }
                }
        }
        return [
                'success' => true,
                'message' => 'Advertisement created successfully. ',
                'data' => [
                    'advertisement' =>$advertisement->load('images')
                        ],
            ];


    }catch(Exception $e){
        return[
                'success' => false,
                'message' => 'Failed to create Advertisement',
                'error' => $e->getMessage()
        ];
    }
    }

    public function viewAdvertisement(int $id)
    {
        try{
        $advertisement = Advertisement::with('images', 'advertisable')->findOrFail($id);

        return [
            'success' => true,
            'data' => $advertisement
        ];
        }catch(Exception $e){
                return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    public function updateAdvertisement(int $id, array $data)
    {
        try{
        $advertisement = Advertisement::findOrFail($id);

        $advertisement->update([
            'title' => $data['title'],
            'body' => $data['body'],
        ]);

        // Update images (optional: delete old and upload new)
        if (isset($data['images'])) {
            $advertisement->images()->delete();
            foreach ($data['images'] as $newImage) {
                if ($newImage->isValid()) {
                    $image=$advertisement->images()->create(['path' => '']);
                    $imageName = time() . $image->id. '.' . $newImage->getClientOriginalExtension();
                    $newImage->move(public_path('advertisements'), $imageName);
                    $imagePath = 'advertisements/' . $imageName;

                    $image->path=$imagePath;
                    $image->save();

                }
            }
        }

        return [
            'success' => true,
            'message' => 'Advertisement updated successfully',
            'data' => $advertisement->fresh('images')
            ];
        }catch(Exception $e){
            return [
                'success' => false,
                'message' => 'Advertisement updated successfully',
                'error' => $e->getMessage()
            ];
        }
    }

    public function deleteAdvertisement(int $id)
    {
        try{
        $advertisement = Advertisement::with('images')->findOrFail($id);

        // Delete associated image files from storage
        foreach ($advertisement->images as $image) {

            if(File::exists($image->path))
            {
                File::delete($image->path);
            }


            // $imagePath = public_path($image->path);
            // if (file_exists($imagePath)) {
            //     unlink($imagePath);
            // }
            $image->delete();
        }

        $advertisement->delete();

        return [
            'success' => true,
            'message' => 'Advertisement deleted successfully'
        ];
    }catch(Exception $e){
        return [
                'success' => false,
                'error' =>$e->getMessage()
            ];
    }
    }

    public function getAllTeacherAdvertisement(){
        try{
            $advertisement=Advertisement::where('advertisable_type',Teacher::class)->orderByDesc('created_at')->with('images')->paginate(3);
            return [
                'success' => true,
                'message' => 'all Teacher Advertisement',
                'data' => $advertisement->fresh('images')
                ];
        }catch(Exception $e){
        return [
                'success' => false,
                'error' =>$e->getMessage()
            ];
        }
    }

        public function getAllCourseAdvertisement(){
        try{
            $advertisement=Advertisement::where('advertisable_type',Course::class)->orderByDesc('created_at')->with('images')->paginate(10);
            return [
                'success' => true,
                'message' => 'all Course Advertisement',
                'data' => $advertisement
                ];
        }catch(Exception $e){
        return [
                'success' => false,
                'error' =>$e->getMessage()
            ];
        }
    }
        public function getAllGeneralAdvertisement(){
        try{
            $advertisement=Advertisement::where('advertisable_type',null)->orderByDesc('created_at')->with('images')->paginate(10);
            return [
                'success' => true,
                'message' => 'all general Advertisement',
                'data' => $advertisement
                ];
        }catch(Exception $e){
        return [
                'success' => false,
                'error' =>$e->getMessage()
            ];
        }
    }



}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Advertisement\AddAdvertisementRequest;
use App\Http\Requests\Advertisement\UpdateAdvertisementRequest;
use App\Models\Advertisement;
use App\Services\AdvertisementService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected AdvertisementService $advertisementService;

    public function __construct(AdvertisementService $advertisementService)
    {
        $this->advertisementService = $advertisementService;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(AddAdvertisementRequest $addAdvertisementRequest)
    {
        $result=$this->advertisementService->createAdvertisement($addAdvertisementRequest->validated());
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error']
            ], 422);
        }

        return response()->json([
            'message' => $result['message'],
            'data' => $result['data']
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $result=$this->advertisementService->viewAdvertisement($id);
        if (!$result['success']) {
            return response()->json([
                'error' => $result['error']
            ], 422);
        }
        return response()->json([
            'data' => $result['data']
        ], 200);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Advertisement $advertisement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id,UpdateAdvertisementRequest $updateAdvertisementRequest)
    {

        $result=$this->advertisementService->updateAdvertisement($id,$updateAdvertisementRequest->validated());
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error']
            ], 422);
        }

        return response()->json([
            'message' => $result['message'],
            'data' => $result['data']
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $result=$this->advertisementService->deleteAdvertisement($id);
        if (!$result['success']) {
            return response()->json([
                'error' => $result['error']
            ], 422);
        }
        return response()->json([
            'message' => $result['message']
        ], 201);


    }
    public function allTeacherAdvertisements(){

        $result=$this->advertisementService->getAllTeacherAdvertisement();
        if (!$result['success']) {
            return response()->json([
                'error' => $result['error']
            ], 422);
        }
        return response()->json([
            'data' => $result['data']
        ], 200);

    }
        public function allCourseAdvertisements(){

        $result=$this->advertisementService->getAllCourseAdvertisement();
        if (!$result['success']) {
            return response()->json([
                'error' => $result['error']
            ], 422);
        }
        return response()->json([
            'data' => $result['data']
        ], 200);

    }
        public function allGeneralAdvertisements(){

        $result=$this->advertisementService->getAllGeneralAdvertisement();
        if (!$result['success']) {
            return response()->json([
                'error' => $result['error']
            ], 422);
        }
        return response()->json([
            'data' => $result['data']
        ], 200);

    }


    public function deleteImage(Request $request){
        $validator = Validator::make($request->all(), [
            'image_id'=>'required|exists:images,id'
        ]);
        if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
        }
        $service=new ImageService;
        $result=$service->deleteImage($request->image_id);
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message']
            ], 422);
        }
        return response()->json([
            'message' => $result['message']
        ], 200);
    }
}

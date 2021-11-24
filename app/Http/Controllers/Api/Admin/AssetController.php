<?php

namespace App\Http\Controllers\Api\Admin;

use DataTables;
use Carbon\Carbon;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            /** @var App\Models\User */
            $currentUser = auth()->user();
            $assetQuery = $currentUser->assets()->with(['farm']);

            // if($request->has('client') && $request->client === 'datatable'){
            //     $assetQuery->select(["*", "assets.id as assetId"]);
            //     return DataTables::eloquent($assetQuery)->editColumn('purchase_date', function($asset){
            //         return $asset->purchase_date->toFormattedDateString();
            //     })
            //     ->setRowId('assetId')
            //     ->editColumn('image', function($asset){
            //         if($asset->image){
            //             return "<img class='h-16 w-full p-1 border text-center rounded shadow' src=". asset($asset->image) .">";
            //         } else {
            //             return "";
            //         }
            //     })
            //     ->rawColumns(['image'])
            //     ->addIndexColumn()->toJson();
            // }


            if($request->has('search') && $request->search != ""){
                $search = $request->search;
                $assetQuery
                    ->where('type', 'like', '%' . $search . '%')
                    ->orWhere('price', 'like', '%' . $search . '%')
                    ->orWhere('purchase_date', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhereHas('farm', function($query) use ($search){
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            }

            if($request->has('sort_field') && $request->has('sort_order')){
                $relationArray = explode(".", $request->sort_field);
                if(count($relationArray) > 1){
                    $relation = $relationArray[0];
                    $field = $relationArray[1];
                    $sortOrder = $request->sort_order;

                    $assetQuery->with([$relation => function($query) use ($field, $sortOrder) {
                        $query->orderBy($field, $sortOrder);
                    }]);
                } else {
                    $assetQuery->orderBy($request->sort_field, $request->sort_order);
                }
            }

            $perPage = $request->has('limit') ? intval($request->limit) : 10;

            $assets = $assetQuery->search()->paginate($perPage);

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $assets
            ]);
        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $data = $this->validate($request, [
                'type' => 'required|string|max:255',
                'price' => 'required|numeric',
                'purchase_date' => 'required|date',
                'location' => 'required|string|max:255',
                'farm_id' => 'required|integer|min:1',
                'image' => 'sometimes|mimes:jpg,jpeg,png,bmp'
            ]);

            /** @var App\Models\User */
            $currentUser = auth()->user();
            $currentUser->farms()->where('farms.id', $request->farm_id)->firstOrFail();

            if(isset($data['image'])){
                $image = $request->file('image')->storePublicly('assets', 'public');
                $data['image'] = $image;
            }

            $asset = Asset::create($data);

            return response()->json([
                'code' => 200,
                'message' => 'Asset Created Successfully',
                'data' => $asset
            ]);
        } catch (ValidationException $exception){
            return response()->json([
                'code' => 422,
                'message' => $exception->getMessage(),
                'data' => $exception->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($asset)
    {
        try {
            /** @var App\Models\User */
            $currentUser = auth()->user();
            $asset = $currentUser->assets()->with(['farm'])->where('assets.id', $asset)->firstOrFail();

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $asset
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Asset Not Found.',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $asset)
    {
        try {

            /** @var App\Models\User */
            $currentUser = auth()->user();
            $asset = $currentUser->assets()->where('assets.id', $asset)->firstOrFail();


            $data = $this->validate($request, [
                'type' => 'string|max:255',
                'price' => 'numeric',
                'purchase_date' => 'date',
                'location' => 'string|max:255',
                'farm_id' => 'integer|min:1',
                'image' => 'sometimes|mimes:jpg,jpeg,png,bmp'
            ]);

            if($request->farm_id && $request->farm_id != $asset->farm_id){
                $currentUser->farms()->where('farms.id', $request->farm_id)->firstOrFail();
            }

            if(isset($data['image'])){
                $image = $request->file('image')->storePublicly('assets', 'public');
                $data['image'] = $image;

                if(Storage::disk('public')->exists($asset->getRawOriginal('image'))){
                    Storage::disk('public')->delete($asset->getRawOriginal('image'));
                }
            }

            $asset->update($data);

            return response()->json([
                'code' => 200,
                'message' => 'Asset Updated Successfully',
                'data' => $asset
            ]);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Asset Not Found.',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        } catch (ValidationException $exception){
            return response()->json([
                'code' => 422,
                'message' => $exception->getMessage(),
                'data' => $exception->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($asset)
    {
        try {
            /** @var App\Models\User */
            $currentUser = auth()->user();
            $asset = $currentUser->assets()->where('assets.id', $asset)->firstOrFail();

            $asset->delete();

            return response()->json([
                "code" => 200,
                "message" => "Asset Deleted Successfully!",
                "data" => null
            ]);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Asset Not Found.',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete Assets using Bulk IDs
     *
     * @param $animal
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        $request->validate([
            'assets' => 'required|array|min:1',
            'assets.*' => 'integer'
        ]);

         /** @var App\Models\User */
         $currentUser = auth()->user();
         $currentUser->assets()->whereIn('assets.id', $request->assets)->delete();

        return response()->success(null, "Assets Deleted Successfully!");
    }
}

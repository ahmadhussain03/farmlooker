<?php

namespace App\Http\Controllers\Api;

use App\Models\FeedType;
use App\Models\OrderFeed;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderFeedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $orderFeedQuery = OrderFeed::query()->with('feedTypes');

            $perPage = $request->has('limit') ? intval($request->limit) : 10;

            $orderFeeds = $orderFeedQuery->paginate($perPage);

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $orderFeeds
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
            $this->validate($request, [
                'name' => 'required|string|max:255|min:2',
                "phone_no" => "required|string|phone:AUTO,SA|max:20",
                'address' => 'required|string',
                'description' => 'required|string',
                'quantity' => 'required|numeric',
                'feed_types' => 'required|array',
                'feed_types.*' => 'required|string'
            ]);

            $orderFeed = OrderFeed::create([
                'name' => $request->name,
                'phone_no' => $request->phone_no,
                'address' => $request->address,
                'description' => $request->description,
                'quantity' => $request->quantity,
                'user_id' => auth()->id()
            ]);

            foreach($request->feed_types as $type){
                FeedType::create([
                    'feed' => $type,
                    'order_feed_id' => $orderFeed->id
                ]);
            }

            $orderFeed->load('feedTypes');

            return response()->json([
                'code' => 200,
                'message' => 'Order Feed Created Successfully',
                'data' => $orderFeed
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
    public function show($order_feed)
    {
        try {
            $orderFeed = OrderFeed::with('feedTypes')->findOrFail($order_feed);

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $orderFeed
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Order Feed Not Found.',
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
    public function update(Request $request, $order_feed)
    {
        try {
            $orderFeed = OrderFeed::with('feedTypes')->findOrFail($order_feed);

            $this->validate($request, [
                'name' => 'nullable|string|max:255|min:2',
                "phone_no" => "nullable|string|phone:AUTO,SA|max:20",
                'address' => 'nullable|string',
                'description' => 'nullable|string',
                'quantity' => 'nullable|numeric',
                'feed_types' => 'nullable|array',
                'feed_types.*' => 'string'
            ]);

            $orderFeed->update(array_diff_key($request->all(), array_flip((array) ['feed_types'])));

            if($request->feed_types && count($request->feed_types) > 0){
                FeedType::where('order_feed_id', $orderFeed->id)->delete();

                foreach($request->feed_types as $type) {
                    FeedType::create([
                        'feed' => $type,
                        'order_feed_id' => $orderFeed->id
                    ]);
                }

                $orderFeed->load('feedTypes');
            }

            return response()->json([
                'code' => 200,
                'message' => 'Order Feed Updated Successfully',
                'data' => $orderFeed
            ]);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Order Feed Not Found.',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        } catch (ValidationException $exception){
            return response()->json([
                'code' => 422,
                'message' => $exception->getMessage(),
                'data' => $exception->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($order_feed)
    {
        try {
            $orderFeed = OrderFeed::findOrFail($order_feed);
            $orderFeed->delete();

            return response()->json([
                "code" => 200,
                "message" => "Order Feed Deleted Successfully!",
                "data" => null
            ]);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Order Feed Not Found.',
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
}

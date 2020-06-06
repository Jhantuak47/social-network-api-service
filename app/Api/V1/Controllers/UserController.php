<?php
namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\approveRejectFriendRequest;
use App\Api\V1\Requests\SentFriendRequest;
use App\Api\V1\Requests\UserListRequest;
use App\Api\V1\Resources\UserListResource;
use App\Models\Friend;
use App\Models\User;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(UserListRequest $request)
    {
        $currUser = auth('api')->user();
        $user = User::where('id', '!=', $currUser->id);

        $user->when($request->filled('filter'), function($query) use($request){
            $query->where(function($q) use ($request){
                $q->where('name', '%'.$request->filter.'%')
                ->orWhere('email', 'like', '%'.$request->filter.'%');  
             });             
        });

        return UserListResource::collection($user->paginate(env('PAGE_SIZE', 10)));
    }

    public function sentFriendRequest(SentFriendRequest $request)
    {
        try {
            $currUser = auth('api')->user();
            $reqUser = User::find($request->request_id);
            if( in_array($reqUser->id, $currUser->friends->pluck('id')->toArray()) ){
                return response()->json([
                    'message' => 'Friend request already send, waiting for response!'
                ], 102);
            }
            $currUser->friends()->attach($reqUser);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::info('sent friend request exception', ['message' => $e->getResponse()]);
            throw new StoreResourceFailedException('Failed to sent Friend request! Please try after some time.');
        }
        return response()->json([
            'message' => 'Friend request is sent successfully!'
        ], 200);
    }

    public function approveRejectFriendRequest(approveRejectFriendRequest $request)
    {
       $currUser = auth('api')->user();
       $friendRequest = Friend::where('user1', $request->user_id)->where('user2', $currUser->id)->where('status', Friend::STATUS_PENDING)->first();
        if($friendRequest){
            $friendRequest->status = $request->status;
            $friendRequest->save();
        }else{
            throw new StoreResourceFailedException('You dont have any pending request!');
        }
        return $this->response()->noContent();
    }

    public function getMutualFriends(User $user)  
    {
        $currentUser = auth('api')->user();

        $friendsIds = $currentUser->friends->where('pivot.status', Friend::STATUS_APPROVED)->pluck('id');
        $mutualFriends = $user->friends/* ->where('pivot.status', Friend::STATUS_APPROVED) */->whereIn('id', $friendsIds);

        return UserListResource::collection($mutualFriends);
    }
}
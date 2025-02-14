<?php

namespace App\Http\Controllers\API;

use App\Models\ChFavorite as Favorite;
use App\Models\User;
use Carbon\Carbon;
use Chatify\Facades\ChatifyMessenger as Chatify;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MessagesController extends Controller
{
    protected int $perPage = 30;

    /**
     * Authenticate the connection for pusher
     */
    public function pusherAuth(Request $request)
    {
        return Chatify::pusherAuth(
            $request->user(),
            Auth::user(),
            $request['channel_name'],
            $request['socket_id']
        );
    }

    public function index($id = null): \Inertia\Response
    {
        $messenger_color = Auth::user()->messenger_color;

        $page = match (auth()->user()->type) {
            User::TYPE_TEACHER => 'Teacher/Chat',
            User::TYPE_ADMIN => 'Admin/Chat',
            default => throw new Exception('Type unknown!'),
        };

        return Inertia::render($page, [
            'id' => $id ?? 0,
            'messengerColor' => $messenger_color ? $messenger_color : Chatify::getFallbackColor(),
            'dark_mode' => Auth::user()->dark_mode < 1 ? 'light' : 'dark',
            'similar_users' => Inertia::lazy(fn () => $this->getSimilarUsers()),
        ]);
    }

    /**
     * Fetch data by id for (user/group)
     *
     * @return JsonResponse
     */
    public function idFetchData(Request $request)
    {
        return auth()->user();
        // Favorite
        $favorite = Chatify::inFavorite($request['id']);

        // User data
        if ($request['type'] == 'user') {
            $fetch = User::where('id', $request['id'])->first();
            if ($fetch) {
                $userAvatar = Chatify::getUserWithAvatar($fetch)->avatar;
            }
        }

        // send the response
        return Response::json([
            'favorite' => $favorite,
            'fetch' => $fetch ?? null,
            'user_avatar' => $userAvatar ?? null,
        ]);
    }

    /**
     * This method to make a links for the attachments
     * to be downloadable.
     *
     * @param  string  $fileName
     * @return JsonResponse
     */
    public function download($fileName)
    {
        $path = config('chatify.attachments.folder').'/'.$fileName;
        if (Chatify::storage()->exists($path)) {
            return response()->json([
                'file_name' => $fileName,
                'download_path' => Chatify::storage()->url($path),
            ], 200);
        } else {
            return response()->json([
                'message' => 'Sorry, File does not exist in our server or may have been deleted!',
            ], 404);
        }
    }

    /**
     * Send a message to database
     *
     * @return JsonResponse response
     */
    public function send(Request $request): JsonResponse
    {
        // default variables
        $error = (object) [
            'status' => 0,
            'message' => null,
        ];
        $attachment = null;
        $attachment_title = null;

        // if there is attachment [file]
        if ($request->hasFile('file')) {
            // allowed extensions
            $allowed_images = Chatify::getAllowedImages();
            $allowed_files = Chatify::getAllowedFiles();
            $allowed = array_merge($allowed_images, $allowed_files);

            $file = $request->file('file');
            // check file size
            if ($file->getSize() < Chatify::getMaxUploadSize()) {
                if (in_array(strtolower($file->extension()), $allowed)) {
                    // get attachment name
                    $attachment_title = $file->getClientOriginalName();
                    // upload attachment and store the new name
                    $attachment = Str::uuid().'.'.$file->extension();
                    $file->storeAs(config('chatify.attachments.folder'), $attachment, config('chatify.storage_disk_name'));
                } else {
                    $error->status = 1;
                    $error->message = 'File extension not allowed!';
                }
            } else {
                $error->status = 1;
                $error->message = 'File size you are trying to upload is too large!';
            }
        }

        if (! $error->status) {
            // send to database
            $message = Chatify::newMessage([
                'type' => $request['type'],
                'from_id' => Auth::user()->id,
                'to_id' => $request['id'],
                'body' => htmlentities(trim($request['message']), ENT_QUOTES, 'UTF-8'),
                'attachment' => ($attachment) ? json_encode((object) [
                    'new_name' => $attachment,
                    'old_name' => htmlentities(trim($attachment_title), ENT_QUOTES, 'UTF-8'),
                ]) : null,
            ]);

            // fetch message to send it with the response
            $messageData = Chatify::parseMessage($message);

            // send to user using pusher
            if (Auth::user()->id != $request['id']) {
                Chatify::push('private-chatify.'.$request['id'], 'messaging', [
                    'from_id' => Auth::user()->id,
                    'to_id' => $request['id'],
                    'message' => $messageData,
                ]);
            }
        }

        // send the response
        return Response::json([
            'status' => '200',
            'error' => $error,
            'message' => $messageData ?? [],
            'tempID' => $request['temporaryMsgId'],
        ]);
    }

    /**
     * fetch [user/group] messages from database
     *
     * @return JsonResponse response
     */
    public function fetch(Request $request): JsonResponse
    {
        $query = Chatify::fetchMessagesQuery($request['id'])->latest();
        $messages = $query->paginate($request->per_page ?? $this->perPage);
        $totalMessages = $messages->total();
        $lastPage = $messages->lastPage();
        $response = [
            'total' => $totalMessages,
            'last_page' => $lastPage,
            'last_message_id' => collect($messages->items())->last()->id ?? null,
            'messages' => $messages->items(),
        ];

        return Response::json($response);
    }

    /**
     * Make messages as seen
     */
    public function seen(Request $request): JsonResponse
    {
        // make as seen
        $seen = Chatify::makeSeen($request['id']);
        // send the response
        return Response::json([
            'status' => $seen,
        ], 200);
    }

    /**
     * Get a contact list
     *
     * @return JsonResponse response
     */
    public function getContacts(Request $request): JsonResponse
    {
        $users = DB::table('users')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('ch_messages')
                    ->whereColumn('users.id', 'ch_messages.from_id')
                    ->orWhereColumn('users.id', 'ch_messages.to_id')
                    ->where(function ($query) {
                        $query->where('ch_messages.from_id', Auth::user()->id)
                            ->orWhere('ch_messages.to_id', Auth::user()->id);
                    });
            })
            ->where('users.id', '!=', Auth::user()->id)
            ->paginate($request->per_page ?? $this->perPage);

        foreach ($users as $user) {
            $latest_message = DB::table('ch_messages')
                ->where('ch_messages.from_id', Auth::user()->id)
                ->where('ch_messages.to_id', $user->id)
                ->orWhere(function ($query) use ($user) {
                    $query->where('ch_messages.from_id', $user->id)
                        ->where('ch_messages.to_id', Auth::user()->id);
                })
                ->orderBy('updated_at', 'desc')
                ->first();

            // Convert timestamps to ISO 8601 format
            if ($latest_message) {
                $latest_message->created_at = Carbon::parse($latest_message->created_at)->toIso8601String();
                $latest_message->updated_at = Carbon::parse($latest_message->updated_at)->toIso8601String();
            }

            $user->latest_message = $latest_message;
        }

        return response()->json([
            'contacts' => $users->items(),
            'total' => $users->total() ?? 0,
            'last_page' => $users->lastPage() ?? 1,
        ], 200);
    }

    /**
     * Put a user in the favorite list
     */
    public function favorite(Request $request): JsonResponse
    {
        $userId = $request['user_id'];
        // check action [star/unstar]
        $favoriteStatus = Chatify::inFavorite($userId) ? 0 : 1;
        Chatify::makeInFavorite($userId, $favoriteStatus);

        // send the response
        return Response::json([
            'status' => @$favoriteStatus,
        ], 200);
    }

    /**
     * Get a favorite list
     */
    public function getFavorites(Request $request): JsonResponse
    {
        $favorites = Favorite::where('user_id', Auth::user()->id)->get();
        foreach ($favorites as $favorite) {
            $favorite->user = User::where('id', $favorite->favorite_id)->first();
            $favorite->user->avatar = Chatify::getUserWithAvatar($favorite->user)->avatar;
        }

        return Response::json([
            'total' => count($favorites),
            'favorites' => $favorites ?? [],
        ], 200);
    }

    /**
     * Search in messenger
     */
    public function search(Request $request): JsonResponse
    {
        $input = trim(filter_var($request['input']));
        $records = User::where('id', '!=', Auth::user()->id)
            ->where('name', 'LIKE', "%{$input}%")
            ->whereIn('type', [User::TYPE_ADMIN, User::TYPE_TEACHER])
            ->paginate($request->per_page ?? $this->perPage);

        $records->getCollection()->transform(function ($record) {
            $record->avatar = Chatify::getUserWithAvatar($record)->avatar;

            return $record;
        });

        return Response::json([
            'records' => $records->items(),
            'total' => $records->total(),
            'last_page' => $records->lastPage(),
        ], 200);
    }

    /**
     * Get shared photos
     */
    public function sharedPhotos(Request $request): JsonResponse
    {
        $images = Chatify::getSharedPhotos($request['user_id']);

        foreach ($images as $image) {
            $image = asset(config('chatify.attachments.folder').$image);
        }
        // send the response
        return Response::json([
            'shared' => $images ?? [],
        ], 200);
    }

    /**
     * Delete conversation
     */
    public function deleteConversation(Request $request): JsonResponse
    {
        // delete
        $delete = Chatify::deleteConversation($request['id']);

        // send the response
        return Response::json([
            'deleted' => $delete ? 1 : 0,
        ], 200);
    }

    public function deleteMessage(Request $request): JsonResponse
    {
        // delete
        $delete = Chatify::deleteMessage($request['id']);

        // send the response
        return Response::json([
            'deleted' => $delete ? 1 : 0,
        ], 200);
    }

    public function updateSettings(Request $request): JsonResponse
    {
        $msg = null;
        $error = $success = 0;

        // dark mode
        if ($request['dark_mode']) {
            $request['dark_mode'] == 'dark'
                ? User::where('id', Auth::user()->id)->update(['dark_mode' => 1])  // Make Dark
                : User::where('id', Auth::user()->id)->update(['dark_mode' => 0]); // Make Light
        }

        // If messenger color selected
        if ($request['messengerColor']) {
            $messenger_color = trim(filter_var($request['messengerColor']));
            User::where('id', Auth::user()->id)
                ->update(['messenger_color' => $messenger_color]);
        }
        // if there is a [file]
        if ($request->hasFile('avatar')) {
            // allowed extensions
            $allowed_images = Chatify::getAllowedImages();

            $file = $request->file('avatar');
            // check file size
            if ($file->getSize() < Chatify::getMaxUploadSize()) {
                if (in_array(strtolower($file->extension()), $allowed_images)) {
                    // delete the older one
                    if (Auth::user()->avatar != config('chatify.user_avatar.default')) {
                        $path = Chatify::getUserAvatarUrl(Auth::user()->avatar);
                        if (Chatify::storage()->exists($path)) {
                            Chatify::storage()->delete($path);
                        }
                    }
                    // upload
                    $avatar = Str::uuid().'.'.$file->extension();
                    $update = User::where('id', Auth::user()->id)->update(['avatar' => $avatar]);
                    $file->storeAs(config('chatify.user_avatar.folder'), $avatar, config('chatify.storage_disk_name'));
                    $success = $update ? 1 : 0;
                } else {
                    $msg = 'File extension not allowed!';
                    $error = 1;
                }
            } else {
                $msg = 'File size you are trying to upload is too large!';
                $error = 1;
            }
        }

        // send the response
        return Response::json([
            'status' => $success ? 1 : 0,
            'error' => $error ? 1 : 0,
            'message' => $error ? $msg : 0,
        ], 200);
    }

    /**
     * Set user's active status
     */
    public function setActiveStatus(Request $request): JsonResponse
    {
        $activeStatus = $request['status'] > 0 ? 1 : 0;
        $status = User::where('id', Auth::user()->id)->update(['active_status' => $activeStatus]);

        return Response::json([
            'status' => $status,
        ], 200);
    }

    public function getSimilarUsers(): Collection
    {
        $loggedInUser = Auth::user();

        $similarUserTypes = [];

        // Check if logged user is admin
        if ($loggedInUser->type == User::TYPE_ADMIN) {
            $admins = User::where('type', User::TYPE_ADMIN)->whereNot('id', $loggedInUser->id)->get()->take(10);
            $similarUserTypes = $admins;
        }

        if ($loggedInUser->type == User::TYPE_TEACHER) {
            // Get the logged in teacher grade
            $levelId = Auth::user()->teacher->load('batchSubjects.batch')->batchSubjects->pluck('batch')->first()->level_id;

            // Get all teachers that are in the same level
            $similarUserTypes = User::where('type', User::TYPE_TEACHER)
                ->whereNot('id', $loggedInUser->id)
                ->whereHas('teacher.batchSubjects.batch', function ($query) use ($levelId) {
                    $query->where('level_id', $levelId);
                })->with('teacher.batchSubjects.subject')->get();
        }

        return $similarUserTypes;
    }
}

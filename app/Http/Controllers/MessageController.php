<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Admin;
use App\Models\MemberCredential;
use App\Models\MessageThread;
use App\Models\MessageThreadParticipant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\Group;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{

    public function index()
    {
        $user = $this->currentUser();

        // Handle the case where no user is authenticated
        if (!$user) {
            // Redirect based on whether the user is an admin or volunteer
            if (\Auth::guard('admin')->check()) {
                return redirect()->route('admin.signin')->with('error', 'You need to be logged in to access this page.');
            } elseif (\Auth::guard('web')->check()) {
                return redirect()->route('volunteer.signin')->with('error', 'You need to be logged in to access this page.');
            }
        }

        // Get or create the admin group chat
        $adminGroupChat = $this->getOrCreateAdminGroupChat();

        // Determine if the user is an admin or a volunteer
        $isAdmin = isset($user->adminID);
        \Log::info('Is Admin: ', [$isAdmin]);
        $userIdField = $isAdmin ? 'adminID' : 'memberCredentialsID';
        \Log::info('User ID: ', [$user->$userIdField]);
        $userType = $isAdmin ? 'admin' : 'volunteer';

        // Fetch message threads where the user is a participant
        $messageThreads = MessageThread::query()
            ->whereIn('id', function ($query) use ($user, $userIdField, $userType) {
                $query->select('message_thread_participants.thread_id')
                    ->from('message_thread_participants')
                    ->where('participant_id', $user->$userIdField)
                    ->where('participant_type', $userType);
            })
            ->with(['latestMessage', 'adminParticipants', 'memberParticipants'])
            ->get();

        // Log debugging information
        \Log::info('Message Threads:', [$messageThreads]);

        foreach ($messageThreads as $thread) {
            \Log::info('Thread ID:', [$thread->id]);
            
            // Get participant IDs for admins
            $adminParticipantIds = \DB::table('message_thread_participants')
                ->where('thread_id', $thread->id)
                ->where('participant_type', 'admin')
                ->pluck('participant_id');
            
            // Get participant IDs for members
            $memberParticipantIds = \DB::table('message_thread_participants')
                ->where('thread_id', $thread->id)
                ->where('participant_type', 'volunteer')
                ->pluck('participant_id');

            // Fetch Admins and Members based on participant IDs
            $adminParticipants = Admin::whereIn('adminID', $adminParticipantIds)->get();
            $memberParticipants = MemberCredential::whereIn('memberCredentialsID', $memberParticipantIds)->get();

            // Attach participants to the thread
            $thread->adminParticipants = $adminParticipants;
            $thread->memberParticipants = $memberParticipants;

            // Determine the other participant in the thread
            $participants = $adminParticipants->merge($memberParticipants);
            $currentUserId = $user->$userIdField;
            
            // Define the function to get the participant's ID
            $getParticipantId = function ($participant) use ($isAdmin) {
                return $isAdmin ? $participant->adminID : $participant->memberCredentialsID;
            };

            $thread->otherParticipant = $participants->first(function ($participant) use ($currentUserId, $getParticipantId) {
                return $getParticipantId($participant) !== $currentUserId;
            });
        }
        
        // Sort threads by the latest message timestamp
        $messageThreads = $messageThreads->sortByDesc(function ($thread) {
            return $thread->latestMessage->created_at ?? null;
        });

        // Return the view based on the user type
        if ($isAdmin) {
            return view('admin.messages', compact('messageThreads', 'adminGroupChat'));
        } else {
            return view('volunteer.messages', compact('messageThreads', 'adminGroupChat'));
        }
    }

    public function store(Request $request)
    {
        \Log::info('Store method called with data:', $request->all());

        $request->validate([
            'receiver_id' => [
                'nullable',
                Rule::when($request->receiver_type === 'admin', 'exists:tbladmin,adminID'),
                Rule::when($request->receiver_type === 'volunteer', 'exists:tblmembercredentials,memberCredentialsID'),
            ],
            'message_content' => 'required|string',
            'receiver_type' => 'nullable',
            'group_id' => 'nullable|exists:message_threads,id',
        ]);

        // Retrieve the current user
        $currentUser = $this->currentUser();
        
        // Determine the sender's ID and type
        $senderId = $currentUser->adminID ?? $currentUser->memberCredentialsID;
        $senderType = $this->getSenderType();
        
        // Check if a thread ID is present
        $isThreadPresent = $request->group_id ? true : false;
        $thread = null;
        \Log::info('Is thread present?: ' . $isThreadPresent);
        if ($isThreadPresent) {
            // Handle Existing Thread
            Log::info('This is a Group chat thread : ');
            $thread = MessageThread::find($request->group_id);
            

            if ($thread->is_group_chat) {
                // It's a group chat
                \Log::info('Group chat thread ID: ' . $thread->id);
                $thread_id = $thread->id;
                 // No specific receiver for group chats
                $receiver_id = 0;
                $receiver_type = 'group_chat'; // Assign a type indicating it's a group chat
            } else {
                // It's a 2-person chat
                // Handle 2-person Chat
                $thread = MessageThread::where('is_group_chat', 0)
                    ->whereExists(function ($query) use ($senderId, $request) {
                        $query->select('thread_id')
                            ->from('message_thread_participants')
                            ->where('participant_id', $senderId)
                            ->whereExists(function ($subquery) use ($request) {
                                $subquery->select('thread_id')
                                    ->from('message_thread_participants')
                                    ->where('participant_id', $request->receiver_id);
                            });
                    })->first();
                \Log::info('2-person chat thread ID: ' . $thread->id);
                $receiver_id = $request->receiver_id;
                $receiver_type = $request->receiver_type;
            }
        } else {
            // Handle New Chat
            $thread = MessageThread::create([
                'name' => null, // No name for individual chat
                'is_group_chat' => false,
            ]);
            
            \Log::info('Created new thread ID: ' . $thread->id);

            // Add participants to the message_thread_participants table
            MessageThreadParticipant::create([
                'thread_id' => $thread->id,
                'participant_id' => $senderId,
                'participant_type' => $senderType,
            ]);
            \Log::info('Added sender to thread ID: ' . $thread->id);
            MessageThreadParticipant::create([
                'thread_id' => $thread->id,
                'participant_id' => $request->receiver_id,
                'participant_type' => $request->receiver_type,
            ]);
            \Log::info('Added receiver to thread ID: ' . $thread->id);
        }

        // Store the message under the thread
        try {
            Message::create([
                'sender_id' => $senderId,
                'receiver_id' => $request->receiver_id ?? $receiver_id, // Use null if not applicable
                'message_content' => $request->message_content,
                'sender_type' => $senderType,
                'receiver_type' => $receiver_type ?? $request->receiver_type , // Use null if not applicable
                'thread_id' => $thread->id ?? $thread_id, // Assign the message to the thread
            ]);
            \Log::info('Message created successfully.');
            return response()->json(['success' => true, 'message' => 'Message sent successfully!']);
        } catch (\Exception $e) {
            \Log::error('Error saving message: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to send message.']);
        }
    }


    public function show($id)
    {
        \Log::info('ID:', ['id' => $id]);
    
        $currentUser = $this->currentUser();
    
        // Retrieve messages by thread ID
        $messages = Message::where('thread_id', $id)
            ->orderBy('created_at', 'desc') // Show oldest messages first
            ->get();
    
        // Initialize variables for the other user's details
        $otherUserName = '';
        $otherUserID = '';
        $otherUserType = '';
        $threadId = $id;
    
        // Check if the thread exists and determine if it's a group chat
        $thread = MessageThread::find($id);
    
        if ($thread) {
            // Handle the case where it's a group chat
            if ($thread->is_group_chat) {
                // Group chat - use thread name as the other participant's name
                $otherUserName = $thread->name;
            } else {
                // Direct message - determine the other user details
                $message = $messages->first();
    
                if ($currentUser->adminID === $message->sender_id) {
                    // Current user is the sender
                    $otherUserName = $message->receiver->first_name . ' ' . $message->receiver->last_name;
                    $otherUserID = $message->receiver_id;
                    $otherUserType = $message->receiver_type;
                } else {
                    // Current user is the receiver
                    $otherUserName = $message->sender->first_name . ' ' . $message->sender->last_name;
                    $otherUserID = $message->sender_id;
                    $otherUserType = $message->sender_type;
                }
            }
        } else {
            \Log::error('Thread not found for ID: ' . $id);
            return response()->json(['error' => 'Thread not found.'], 404);
        }
    
        // Return response with the appropriate data
        return response()->json([
            'messages' => $messages,
            'other_user_name' => $otherUserName,
            'other_user_id' => $otherUserID,
            'other_user_type' => $otherUserType,
            'group_chat_name' => $thread->name ?? null, // If it's a group chat
            'group_id' => $threadId,
            'current_user_id' => $currentUser->adminID ?? $currentUser->memberCredentialsID,
        ]);
    }
    
    public function edit($id)
    {
        $message = Message::findOrFail($id);
        return view('admin.messages', array_merge($this->getAdminAndVolunteers(), compact('message')));
    }

    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        $message->update($request->only('receiver_id', 'message_content', 'receiver_type'));

        return redirect()->route('admin.messages')->with('success', 'Message updated successfully!');
    }

    public function destroy($id)
    {
        Message::findOrFail($id)->delete();
        return redirect()->route('admin.messages')->with('success', 'Message deleted successfully!');
    }

    private function getOrCreateAdminGroupChat()
    {
        $user = $this->currentUser();

        // Handle the case where no user is authenticated
        if (!$user) {
            // Redirect based on whether the user is an admin or volunteer
            if (\Auth::guard('admin')->check()) {
                return redirect()->route('admin.signin')->with('error', 'You need to be logged in to access this page.');
            } elseif (\Auth::guard('web')->check()) {
                return redirect()->route('volunteer.signin')->with('error', 'You need to be logged in to access this page.');
            }
        }
        // Check if a group chat with the name "SOCI Group Chat" already exists
        $adminGroupChat = MessageThread::where('is_group_chat', true)
                                        ->where('name', 'SOCI Group Chat')
                                        ->first();

        // If no such group chat exists, create it
        if (!$adminGroupChat) {
            $adminGroupChat = MessageThread::create([
                'name' => 'SOCI Group Chat',
                'is_group_chat' => true,
            ]);

            // Call the method to add all participants to the chat
            $this->addAllParticipantsToGroup($adminGroupChat);
        }

        // Check if the current user is already a participant of the group chat
        $isParticipant = MessageThreadParticipant::where('thread_id', $adminGroupChat->id)
            ->where('participant_id', $user->adminID ?? $user->memberCredentialsID ?? '')
            ->where('participant_type', $this->getSenderType()) // Check the type (admin/volunteer)
            ->exists();
            \Log::info('partcipant :', ['partcipant' => $isParticipant]);
        // If the current user is not a participant, add them to the group chat
        if (!$isParticipant) {
            MessageThreadParticipant::create([
                'thread_id' => $adminGroupChat->id,
                'participant_id' => $user->adminID ?? $user->memberCredentialsID ?? '',
                'participant_type' => $this->getSenderType(),
            ]);
            $this->sendWelcomeMessage($adminGroupChat, $isParticipant);
        }
        return $adminGroupChat;
    }

    private function addAllParticipantsToGroup($groupChat)
    {
        // Gather all admin and volunteer IDs and set their participant type
        $adminParticipants = Admin::all()->pluck('adminID')
            ->filter()
            ->map(function ($id) {
                return ['participant_id' => $id, 'participant_type' => Admin::class];
            })
            ->toArray();

        $volunteerParticipants = MemberCredential::all()->pluck('memberCredentialsID')
            ->filter()
            ->map(function ($id) {
                return ['participant_id' => $id, 'participant_type' => MemberCredential::class];
            })
            ->toArray();

        // Combine the participants
        $participants = array_merge($adminParticipants, $volunteerParticipants);

        // Attach participants if they don't already exist
        foreach ($participants as $participant) {
            \DB::table('message_thread_participants')->updateOrInsert(
                [
                    'participant_id' => $participant['participant_id'],
                    'participant_type' => $participant['participant_type'],
                    'thread_id' => $groupChat->id
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
            
        }
        // Send a welcome message after all participants have been added
        $this->sendWelcomeMessage($groupChat, $user = null);
    }

    public function addParticipantToGroupChat($user)
    {
        // Get the admin group chat
        $groupChat = $this->getOrCreateAdminGroupChat();

        // Check if the user is already in the group chat
        $existingParticipant = \DB::table('message_thread_participants')
            ->where('thread_id', $groupChat->id)
            ->where('participant_id', $user->adminID ?? $user->memberCredentialsID)
            ->where('participant_type', $this->getSenderType())
            ->first();
        \Log::info('This is the existing participant: ', [$existingParticipant]);
        if (!$existingParticipant) {
            // Add the new participant to the group chat
            \DB::table('message_thread_participants')->insert([
                'participant_id' => $user->adminID ?? $user->memberCredentialsID,
                'participant_type' => $this->getSenderType(),
                'thread_id' => $groupChat->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Send a welcome message to the group chat
            // $messageContent = "Welcome, " . $user->name . ", to the SOCI Group Chat!";
            $this->sendWelcomeMessage($groupChat, $user);
        }
    }

    private function sendWelcomeMessage($groupChat, $newUser)
    {
        // If a new user is being added, customize the message for them
        if ($newUser) {
            $messageContent = "Welcome, " . $newUser->name . ", to the SOCI Group Chat!";
            Log::info("Sending welcome message to new user: {$messageContent}");
            $this->createMessage(null, $groupChat->id, $messageContent, $this->getSenderType(), $newUser->adminID ?? $newUser->memberCredentialsID);
        } else {
            // If no new user is specified, send a general welcome message to all participants
            $messageContent = "Welcome to the SOCI Group Chat! We're glad to have you here.";
            Log::info("Sending welcome message to participant: {$messageContent}");
                Log::info("Sending welcome message to participant: {$messageContent}");
                $this->createMessage(null, $groupChat->id, $messageContent, 'system' , 0);
            
        }
    }

    // Helper function to create a message
    private function createMessage($senderId, $threadId, $messageContent, $receiverType, $receiverId)
    {
        Message::create([
            'sender_id' => $senderId ?? 0,
            'receiver_id' => $receiverId,
            'message_content' => $messageContent,
            'sender_type' => 'system',
            'receiver_type' => $receiverType,
            'thread_id' => $threadId,
            'created_at' => now(),
        ]);
    }

    private function getAdminAndVolunteers()
    {
        return [
            'admins' => Admin::all(),
            'volunteers' => MemberCredential::all(),
            'receiverId' => null,
            'receiverType' => null,
        ];
    }

    private function getSenderType()
    {
        if (Auth::guard('admin')->check()) {
            return 'admin';
        } elseif (Auth::guard('web')->check()) {
            return 'volunteer';
        }
        return null; // Default or handle accordingly
    }

    function currentUser() {
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user();
        } elseif (Auth::guard('web')->check()) {
            return Auth::guard('web')->user();
        }
        return null;
    }

    //Search User (to message) function 
    public function searchUsers(Request $request)
    {
        $user = $this->currentUser();
        $query = $request->get('query');
        $isAdmin = isset($user->adminID);
        $currentUserId = $isAdmin ? $user->adminID : $user->memberCredentialsID;
        $currentUserType = $isAdmin ? 'admin' : 'volunteer';
    
        // Fetch admins based on the query, excluding the current user if they are an admin
        $admins = Admin::where(function ($q) use ($query) {
                            $q->where('first_name', 'LIKE', "%{$query}%")
                              ->orWhere('middle_name', 'LIKE', "%{$query}%")
                              ->orWhere('last_name', 'LIKE', "%{$query}%");
                        })
                        ->when($currentUserType === 'admin', function ($q) use ($currentUserId) {
                            return $q->where('adminID', '!=', $currentUserId);
                        })
                        ->get();
    
        // Fetch volunteers based on the query, excluding the current user if they are a volunteer
        $volunteers = MemberCredential::where(function ($q) use ($query) {
                            $q->where('first_name', 'LIKE', "%{$query}%")
                              ->orWhere('middle_name', 'LIKE', "%{$query}%")
                              ->orWhere('last_name', 'LIKE', "%{$query}%");
                        })
                        ->when($currentUserType === 'volunteer', function ($q) use ($currentUserId) {
                            return $q->where('memberCredentialsID', '!=', $currentUserId);
                        })
                        ->get();
    
        // Map the search results and check for an existing message thread for each user
        $mappedUsers = $admins->concat($volunteers)->map(function ($otherUser) use ($currentUserId, $currentUserType) {
            $otherUserId = $otherUser instanceof Admin ? $otherUser->adminID : $otherUser->memberCredentialsID;
            $otherUserType = $otherUser instanceof Admin ? 'admin' : 'volunteer';
    
            // Query for existing message thread between the current user and the searched user
            $existingThread = MessageThread::where('is_group_chat', false)
                ->whereIn('id', function ($query) use ($currentUserId, $currentUserType) {
                    $query->select('thread_id')
                          ->from('message_thread_participants')
                          ->where('participant_id', $currentUserId)
                          ->where('participant_type', $currentUserType);
                })
                ->whereIn('id', function ($query) use ($otherUserId, $otherUserType) {
                    $query->select('thread_id')
                          ->from('message_thread_participants')
                          ->where('participant_id', $otherUserId)
                          ->where('participant_type', $otherUserType);
                })
                ->first();
    
            return [
                'id' => $otherUserId,
                'name' => "{$otherUser->first_name} {$otherUser->middle_name} {$otherUser->last_name}",
                'type' => $otherUserType,
                'thread_id' => $existingThread ? $existingThread->id : null // Include thread ID if exists
            ];
        });
    
        return response()->json($mappedUsers->values());
    }    
}

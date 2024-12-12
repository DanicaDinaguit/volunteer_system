@extends('layouts.volunteer_app')

@section('title', 'Volunteer Messages')
    
@section('content')
    <div id="messages" style="margin: 0 auto; margin-top: 40px !important;margin-bottom: 40px !important;  display: flex; border-radius: 8px; width: 80%;">
        <div class="message-recepient">
            <div class="recepient-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div><h2>Messages</h2></div>
                <div id="new-message-icon" data-bs-toggle="modal" data-bs-target="#userSelectModal" style="cursor: pointer;">
                    <svg viewBox="0 0 12 13" width="20" height="20" fill="currentColor" class="x19dipnz x1lliihq x1tzjh5l x1k90msu x2h7rmj x1qfuztq" style="--color: var(--primary-icon);"><g fill-rule="evenodd" transform="translate(-450 -1073)"><g><path d="M105.506 926.862a.644.644 0 0 1-.644.644h-6.724a.644.644 0 0 1-.644-.644v-6.724c0-.356.288-.644.644-.644h2.85c.065 0 .13-.027.176-.074l.994-.993a.25.25 0 0 0-.177-.427h-3.843A2.138 2.138 0 0 0 96 920.138v6.724c0 1.18.957 2.138 2.138 2.138h6.724a2.138 2.138 0 0 0 2.138-2.138v-3.843a.25.25 0 0 0-.427-.177l-1.067 1.067v2.953zm1.024-9.142a.748.748 0 0 0-1.06 0l-.589.588a.25.25 0 0 0 0 .354l1.457 1.457a.25.25 0 0 0 .354 0l.588-.589a.75.75 0 0 0 0-1.06l-.75-.75z" transform="translate(354.5 156)"></path><path d="M99.22 923.97a.75.75 0 0 0-.22.53v.75c0 .414.336.75.75.75h.75a.75.75 0 0 0 .53-.22l4.248-4.247a.25.25 0 0 0 0-.354l-1.457-1.457a.25.25 0 0 0-.354 0l-4.247 4.248z" transform="translate(354.5 156)"></path></g></g></svg>
                </div>
            </div>
            <div class="recepient">
                @php
                    // Determine the current user ID based on the guard in use
                    $currentUserId = Auth::guard('admin')->check() ? Auth::guard('admin')->user()->adminID : (Auth::guard('web')->check() ? Auth::guard('web')->user()->memberCredentialsID : null);
                    Log::info('Current User ID:', ['currentUserId' => $currentUserId]);
                @endphp

                <!-- Display all message threads -->
                @foreach($messageThreads as $thread)
                    @if($thread->is_group_chat)
                        <div class="recepient-name" data-message-id="{{ $thread->id }}" style="cursor: pointer;">
                            <img src="{{asset('images/messageProfile.png')}}" alt="" style="width: 66px; height: 56px;">
                            <h3>{{ $thread->name }}</h3>
                            <p>{{$thread->latestMessage->created_at->diffForHumans()}}</p>
                        </div>
                    @else
                        @if($thread->otherParticipant)
                            <div class="recepient-name" 
                            data-chat-id="{{ $thread->id }}" 
                            data-participant-id="{{ $thread->otherParticipant->id }}" 
                            data-participant-type="{{ get_class($thread->otherParticipant) === 'App\\Models\\Admin' ? 'admin' : 'volunteer' }}"
                            style="cursor: pointer;">
                            @if(!$thread->otherParticipant->profile_image)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="64" height="64">
                                    <circle cx="12" cy="12" r="10" fill="#e0e0e0"/>
                                    <circle cx="12" cy="8" r="4" fill="#9e9e9e"/>
                                    <path d="M16 16c0-2.2-1.8-4-4-4s-4 1.8-4 4v2h8v-2z" fill="#9e9e9e"/>
                                </svg>
                            @else
                                <img src="{{ $thread->otherParticipant->profile_image }}" alt="Participant Image">
                            @endif
                                <h3>{{ $thread->otherParticipant->first_name }} {{ $thread->otherParticipant->last_name }}</h3>
                                <p>{{ $thread->latestMessage->created_at->diffForHumans() }} ?? ''</p>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>


        </div>
        <div class="message-content" style="width: 75%; background-color: #fff; padding: 20px; display: flex; flex-direction: column;">
            <div class="message-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                
                <div class="receiver-name">
                    <h3 id="receiver-name"></h3>
                </div>
                <div>
                    <svg style="cursor: pointer;" xmlns="http://www.w3.org/2000/svg" width="14" height="28" viewBox="0 0 14 28" fill="none">
                        <g filter="url(#filter0_d_794_1524)">
                            <circle cx="6.72728" cy="2.72728" r="2.72728" fill="#AB2695"/>
                            <circle cx="6.72728" cy="10.0007" r="2.72728" fill="#AB2695"/>
                            <circle cx="6.72728" cy="17.2722" r="2.72728" fill="#AB2695"/>
                        </g>
                        <defs>
                            <filter id="filter0_d_794_1524" x="0.363636" y="0" width="12.7268" height="27.2727" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                            <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                            <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                            <feOffset dy="3.63636"/>
                            <feGaussianBlur stdDeviation="1.81818"/>
                            <feComposite in2="hardAlpha" operator="out"/>
                            <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                            <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_794_1524"/>
                            <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_794_1524" result="shape"/>
                            </filter>
                        </defs>
                    </svg>
                </div>
            </div> 

            <!-- Updated message-body for conversation-like layout -->
            <div class="message-body" id="message-body">
                <!-- Messages will be dynamically inserted here -->
            </div>

            <div class="message-send">
                <!-- <div class="emoji">
                    <svg style="cursor: pointer;" xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none">
                    <g clip-path="url(#clip0_923_1294)">
                        <g filter="url(#filter0_d_923_1294)">
                        <path d="M27.9997 51.9057C41.0547 51.9057 51.9057 41.0778 51.9057 27.9998C51.9057 14.9447 41.0307 4.09375 27.9757 4.09375C14.8987 4.09375 4.09473 14.9447 4.09473 27.9998C4.09473 41.0778 14.9217 51.9057 27.9997 51.9057ZM28.0007 47.9218C16.9367 47.9218 8.09973 39.0618 8.09973 27.9998C8.09973 16.9598 16.9127 8.07775 27.9757 8.07775C39.0147 8.07775 47.8967 16.9608 47.9207 27.9998C47.9437 39.0628 39.0377 47.9218 27.9987 47.9218M21.1787 25.8668C22.5607 25.8668 23.7087 24.6478 23.7087 22.9368C23.7087 21.2267 22.5607 20.0068 21.1787 20.0068C19.8187 20.0068 18.6937 21.2267 18.6937 22.9368C18.6937 24.6478 19.8187 25.8668 21.1787 25.8668ZM34.8887 25.8668C36.2717 25.8668 37.4207 24.6478 37.4207 22.9368C37.4207 21.2267 36.2717 20.0068 34.8897 20.0068C33.5067 20.0068 32.3817 21.2267 32.3817 22.9368C32.3817 24.6478 33.5057 25.8668 34.8887 25.8668ZM27.9997 39.2968C33.6477 39.2968 37.1867 35.2887 37.1867 33.7657C37.1867 33.4607 36.9527 33.3197 36.7187 33.5077C35.0077 34.9377 32.1247 36.3438 27.9987 36.3438C23.8507 36.3438 20.8987 34.8437 19.2577 33.5307C19.0227 33.3207 18.7887 33.4607 18.7887 33.7657C18.7887 35.2887 22.3277 39.2958 27.9987 39.2958" fill="#BD2382"/>
                        </g>
                    </g>
                    <defs>
                        <filter id="filter0_d_923_1294" x="0.0947266" y="4.09375" width="55.8105" height="55.8125" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                        <feOffset dy="4"/>
                        <feGaussianBlur stdDeviation="2"/>
                        <feComposite in2="hardAlpha" operator="out"/>
                        <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_923_1294"/>
                        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_923_1294" result="shape"/>
                        </filter>
                        <clipPath id="clip0_923_1294">
                        <rect width="56" height="56" fill="white"/>
                        </clipPath>
                    </defs>
                    </svg>
                </div> -->
                <form id="message-form">
                    @csrf
                    <div class="message-input" style="width: 100%">
                        <input type="text" name="message_content" id="message_content" required>
                    </div>
                    <input type="hidden" name="receiver_id" id="receiver_id" value="{{ $receiver_id ?? '' }}">
                    <input type="hidden" name="group_id" id="group_id" value="{{ $group_id ?? '' }}">
                    <input type="hidden" name="receiver_type" id="receiver_type" value="{{ $receiver_type ?? '' }}">
                    <div>
                        <button type="submit" class="svg-button"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <!-- Modal for Selecting Users to Chat With -->
    <div class="modal fade" id="userSelectModal" tabindex="-1" aria-labelledby="userSelectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userSelectModalLabel">Select User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="user-search" class="form-control" placeholder="Search users..." >
                    <div id="user-list" style="margin-top: 15px;">
                        <!-- Dynamically load users here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Handle clicks on recipient names or group chat buttons
        document.querySelectorAll('.recepient-name, .group-message').forEach(item => {
            item.addEventListener('click', function() {
                const messageId = this.getAttribute('data-message-id') || this.getAttribute('data-chat-id');
                fetchMessageContent(messageId);
            });
        });

        // Function to fetch message content and display it in the message body
        function fetchMessageContent(messageId) {
            fetch(`/admin/messages/${messageId}`, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                const messageBody = document.getElementById('message-body');
                messageBody.innerHTML = ''; // Clear the message body before inserting new messages

                // Loop through the messages
                data.messages.forEach(message => {
                    const messageDiv = document.createElement('div');
                    const currentUserId = {{ $currentUserId }};

                        if (message.sender_id == currentUserId) {
                            messageDiv.classList.add('message-sent');
                            messageDiv.innerHTML = `<div class="message-box">${message.message_content}</div>`;
                        } else {
                            messageDiv.classList.add('message-received');
                            messageDiv.innerHTML = `<div class="message-box">${message.message_content}</div>`;
                        }
                    messageBody.appendChild(messageDiv);
                });

                // Scroll to the bottom of the message body
                messageBody.scrollTop = messageBody.scrollHeight;

                // Update the recipient's name or group chat name
                document.getElementById('receiver-name').textContent = data.group_chat_name || data.other_user_name;
                document.querySelector('input[name="receiver_id"]').value = data.other_user_id;
                document.querySelector('input[name="group_id"]').value = data.group_id;
                document.querySelector('input[name="receiver_type"]').value = data.other_user_type;
            })
            .catch(error => console.error('Error fetching message content:', error));
        }

        document.getElementById('message-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form from refreshing the page

            // Get form data
            const messageContent = document.getElementById('message_content').value;
            console.log('message content', messageContent);
            const receiverId = document.getElementById('receiver_id').value;
            console.log('Receiver_ID', receiverId);
            const groupId = document.getElementById('group_id').value;
            console.log('Group ID', groupId);
            const receiverType = document.getElementById('receiver_type').value;
            console.log('Receiver Type', receiverType);

            // Send AJAX request
            fetch('{{ route('messages.stores') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message_content: messageContent,
                    receiver_id: receiverId,
                    group_id: groupId,
                    receiver_type: receiverType
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Unknown error');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Clear the input field after successful 
                    console.log('Message sent:', data);
                    document.getElementById('message_content').value = '';
                    // Optionally, append the new message to the message list without refreshing
                } else {
                    console.error('Error sending message:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });


        // Function to search for users
        function searchUsers() {
            let query = document.getElementById('user-search').value;
            fetch(`/admin/search-users?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    let userHtml = '';
                    data.forEach(user => {
                        const userId = user.id ?? 'Unknown ID';
                        const userName = user.name ?? 'Unknown Name';
                        const userType = user.type ?? 'Unknown Type';
                        const userThread = user.thread_id ?? 'No existing thread';

                        userHtml += `<div class="user-item" style="cursor: pointer;" onclick="selectUser(${userId}, '${userName}', '${userType}', ${userThread})">${userName}</div>`;
                    });
                    document.getElementById('user-list').innerHTML = userHtml;
                })
                .catch(error => {
                    console.error('Error fetching users:', error);
                });
        }
        // Attach event listener to the search input
        document.getElementById('user-search').addEventListener('keyup', searchUsers);

        // Function to handle user selection
        function selectUser(userId, userName, userType, threadId = null) {
            // You can close the modal and initiate a new chat with the selected user
            console.log('User selected:', userId, userName);
            // Handle chat initiation logic here
            $('#userSelectModal').modal('hide');
            
            document.getElementById('receiver-name').textContent = userName;
            document.getElementById('receiver_id').value = userId;
            document.getElementById('receiver_type').value = userType;
            // document.getElementById('group-id').textContent = groupId;

            /// Check if a thread exists with this user
            if (threadId) {
                // Fetch and display existing messages in the thread
                fetchMessageContent(threadId);
            } else {
                // If no existing thread, clear the message display
                document.getElementById('message-body').innerHTML = '';
            }
        }       
    </script>
@endsection
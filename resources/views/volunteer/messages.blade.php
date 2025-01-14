@extends('layouts.volunteer_app')

@section('title', 'Volunteer Messages')
    
@section('styles')
    <style>
        /* Container to position dropdown relative to the icon */
        .dropdown-container {
            position: relative;
            display: inline-block;
        }

        /* Dropdown menu styling */
        .dropdown-menu {
            position: absolute;
            top: 100%; /* Positions the dropdown directly below the icon */
            left: 0;
            display: none;
            background-color: #fff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            padding: 8px 0;
            min-width: 120px;
            z-index: 10;
        }

        /* Style dropdown items */
        .dropdown-menu button {
            background: none;
            border: none;
            padding: 8px 16px;
            text-align: left;
            width: 100%;
            cursor: pointer;
            color: #333;
        }

        .dropdown-menu button:hover {
            background-color: #f5f5f5;
        }
        .message-time {
            font-size: 0.8em;
            color: #888;
            text-align: right;
            margin-top: 5px;
        }
    </style>
@endsection

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
        <div class="message-content" style="width: 70%; background-color: #fff; padding: 20px; display: flex; flex-direction: column;">
            <div class="message-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                
                <div class="receiver-name">
                    <h3 id="receiver-name"></h3>
                </div>
                <div class="dropdown-container">
                    <!-- Svg for 3 dots option -->
                    <svg style="cursor: pointer;" id="three-dot-icon" onclick="toggleDropdown()" xmlns="http://www.w3.org/2000/svg" width="14" height="28" viewBox="0 0 14 28" fill="none">
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

                    <!-- Dropdown menu -->
                    <div id="dropdown-menu" class="dropdown-menu">
                        <button class="delete-btn" onclick="deleteMessage()" data-id="">Delete</button>
                    </div>
                </div>
                
            </div> 

            <!-- Updated message-body for conversation-like layout -->
            <div class="message-body" id="message-body" style="flex-grow: 1; overflow-y: auto; max-height: 500px;">
                <!-- Messages will be dynamically inserted here -->
            </div>

            <div class="message-send">
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
       document.querySelectorAll('.recepient-name').forEach(item => {
            item.addEventListener('click', function() {
                const messageId = this.getAttribute('data-message-id') || this.getAttribute('data-chat-id');
                currentPage = 1; // Reset pagination
                hasMorePages = true; // Reset pagination tracking
                document.getElementById('message-body').scrollTop = 0;
                fetchMessageContent(messageId);
            });
        });

        // Function to fetch message content and display it in the message body
        let currentPage = 1; // Track the current page
        let hasMorePages = true; // Track if there are older messages to load

        function fetchMessageContent(messageId, appendToTop = false) {
            fetch(`/admin/messages/${messageId}?page=${currentPage}`, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                const messageBody = document.getElementById('message-body');
                
                // If it's the initial fetch, clear the message body
                if (!appendToTop) {
                    messageBody.innerHTML = ''; // Clear the message body
                }

                // Insert messages
                data.messages.forEach(message => {
                    const messageDiv = document.createElement('div');
                    const currentUserId = {{ $currentUserId }};

                    if (message.sender_id == currentUserId) {
                        messageDiv.classList.add('message-sent');
                        messageDiv.innerHTML = `
                        <div class="message-time">${message.time_human_readable}</div>
                        <div class="message-box">${message.message_content}</div>`;
                    } else {
                        messageDiv.classList.add('message-received');
                        messageDiv.innerHTML = `
                        <div class="message-time">${message.time_human_readable}</div>
                        <div class="message-box">${message.message_content}</div>`;
                    }

                    if (appendToTop) {
                        const currentScrollHeight = messageBody.scrollHeight;
                        messageBody.prepend(messageDiv); // Prepend older messages
                        const newScrollHeight = messageBody.scrollHeight;
                        messageBody.scrollTop += newScrollHeight - currentScrollHeight;
                    } else {
                        messageBody.appendChild(messageDiv); // Append for new messages
                    }
                });

                // Update the recipient's name or group chat name
                document.getElementById('receiver-name').textContent = data.group_chat_name || data.other_user_name;
                document.querySelector('input[name="receiver_id"]').value = data.other_user_id;
                document.querySelector('input[name="group_id"]').value = data.group_id;
                document.querySelector('input[name="receiver_type"]').value = data.other_user_type;
                document.querySelector('.delete-btn').setAttribute('data-id', data.group_id);

                // Scroll to the bottom initially
                if (!appendToTop) {
                    messageBody.scrollTop = messageBody.scrollHeight;
                }

                // Update pagination info
                hasMorePages = data.has_more_pages;
            })
            .catch(error => console.error('Error fetching message content:', error));
        }

        let isFetchingOlderMessages = false; // Lock to prevent duplicate requests
        let lastScrollTop = 0; // Track the last scroll position

        function loadOlderMessagesOnScroll() {
            const messageBody = document.getElementById('message-body');

            // Only fetch if user is at the top and no fetch is currently ongoing
            if (messageBody.scrollTop <= 20 && hasMorePages && !isFetchingOlderMessages) {
                isFetchingOlderMessages = true; // Set lock to prevent duplicate requests

                const previousHeight = messageBody.scrollHeight; // Record current scroll height
                currentPage++; // Increment page for older messages
                const messageId = document.querySelector('input[name="group_id"]').value;

                fetchMessageContent(messageId, true).then(() => {
                    // Adjust scroll position to maintain user's view after loading
                    messageBody.scrollTop = messageBody.scrollHeight - previousHeight;
                    isFetchingOlderMessages = false; // Release lock after fetch completes
                }).catch(error => {
                    console.error('Error fetching older messages:', error);
                    isFetchingOlderMessages = false; // Release lock on error
                });
            }
        }

        document.getElementById('message-body').addEventListener('scroll', function (event) {
            const messageBody = event.target;

            // Only trigger loadOlderMessagesOnScroll if user scrolls up towards the top
            if (messageBody.scrollTop < lastScrollTop) {
                // If the scroll direction is upwards, check for older messages
                loadOlderMessagesOnScroll();
            }

            // Update last scroll position
            lastScrollTop = messageBody.scrollTop;
        });

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
            fetch('{{ route('messages.store') }}', {
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
                    appendNewMessage(messageContent);
                } else {
                    console.error('Error sending message:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        // Function to append the new message to the message list on the screen
        function appendNewMessage(messageContent) {
            const messageBody = document.getElementById('message-body'); // The element containing the message list

            // Create a new message div structure similar to your provided format
            const newMessageDiv = document.createElement('div');
            newMessageDiv.classList.add('message-sent'); // Add the class for sent messages

            const messageBoxDiv = document.createElement('div');
            messageBoxDiv.classList.add('message-box'); // Add the class for the message box
            messageBoxDiv.innerText = messageContent; // Set the message content

            // Append the message box to the new message div
            newMessageDiv.appendChild(messageBoxDiv);

            // Append the new message to the message body
            messageBody.appendChild(newMessageDiv);
            console.log(messageBody.innerHTML);
            // Optionally scroll to the bottom to show the latest message
            messageBody.scrollTop = messageBody.scrollHeight;
        }

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
                        const userThread = user.thread_id ?? '';

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
            document.getElementById('group-id').textContent = groupId;

            /// Check if a thread exists with this user
            if (threadId) {
                // Fetch and display existing messages in the thread
                fetchMessageContent(threadId);
            } else {
                // If no existing thread, clear the message display
                document.getElementById('message-body').innerHTML = '';
            }
        }  
        
        function toggleDropdown() {
            const dropdown = document.getElementById("dropdown-menu");
            dropdown.style.display = dropdown.style.display === "none" ? "block" : "none";
        }

        // Close the dropdown when clicking outside of it
        document.addEventListener("click", function(event) {
            const dropdown = document.getElementById("dropdown-menu");
            if (!dropdown.contains(event.target) && event.target.closest('svg') === null) {
                dropdown.style.display = "none";
            }
        });

        function deleteMessage() {
            const id = document.querySelector('input[name="group_id"]').value;

            if (!id) {
                alert("No message ID found.");
                return;
            }

            if (confirm("Are you sure you want to delete this message thread?")) {
                fetch(`/admin/messages/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => {
                    if (response.ok) {
                        alert("Message thread deleted successfully.");
                        // Optionally refresh the page or remove the thread from the UI
                        location.reload();
                    } else {
                        alert("Failed to delete the message thread.");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while deleting the message thread.");
                });
            }
        }   
    </script>
@endsection
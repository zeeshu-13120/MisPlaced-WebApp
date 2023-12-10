@extends('Layout.layout')
@section('title','Chat')

@section('style')
@include('Chat.css')
@endsection
@section('content')
    <!-- char-area -->
        <div class="container py-4">
            <div class="row">
                <div class="col-12 ">

                    <section class="rounded-3 chat-area">

                        <!-- chatlist -->
                        <div class="chatlist border-end">
                            <div class="modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="chat-header">
                                        <h3 class="text-center">Recent Chats</h3>


                                    </div>

                                    <div class="modal-body">
                                        <!-- chat-list -->
                                        <div class="chat-lists">
                                            <div class="tab-content" id="myTabContent">
                                                <div class="tab-pane fade show active" id="Open" role="tabpanel" aria-labelledby="Open-tab">
                                                    <!-- chat-list -->
                                                    <div class="chat-list">




                                                    </div>
                                                    <!-- chat-list -->
                                                </div>

                                            </div>

                                        </div>
                                        <!-- chat-list -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- chatbox -->
                        <div class="chatbox d-none">
                            <div class="modal-dialog-scrollable">

                                <input type="text" hidden id="post1">
                                <input type="text" hidden id="post2">
                                <input type="text" hidden id="databaseTable">

                                <div class="modal-content">
                                    <div class="msg-head bg-primary text-white">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="d-flex  align-items-center">
                                                    <span class="chat-icon"><iconify-icon icon="ion:arrow-back-sharp" width="35px" height="35px" role="button"></iconify-icon></span>
                                                    <div class="bg-white rounded-circle">
                                                        <img class="rounded-circle" id="chat-avatar" width="60px" height="60px" src="" alt="user img">
                                                    </div>
                                                    <div class="flex-grow-1 ms-3 text-white">
                                                        <h3 id="name" class="text-white"></h3>
                                                        <p class="text-white">Last Seen: </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <ul class="moreoption">
                                                    <li class="navbar nav-item dropdown">
                                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="#">Action</a></li>
                                                            <li><a class="dropdown-item" href="#">Another action</a></li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="modal-body">
                                        <div class="msg-body">
                                            <ul class='message-container'>
                                                <div style="height: 400px", class="d-flex align-items-center justify-content-center">

                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>


                                            </ul>
                                        </div>
                                    </div>


                                    <div class="send-box">
                                        <form action="" id="message-form" class="">
                                            <input type="text" hidden name="" value="" id="chat_id">
                                            <input type="text" hidden name="" value="" id="user_id">
                                            <input type="text" hidden name="" value="" id="user_token">
                                            <input type="text" class="form-control" id="message-input" aria-label="message…" placeholder="Write message…">
<!-- Mic icon for voice recording -->
<div class="mic-icon" id="mic-icon" role="button">
    <iconify-icon icon="ion:mic" width="30px" height="30px"></iconify-icon>
</div>

<!-- Recording modal using Bootstrap 5 -->
<div class="modal fade" id="recordingModal" tabindex="-1" aria-labelledby="recordingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recordingModalLabel">Recording...</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Please wait while we record your message...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="stopRecording()" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="send-recording-btn">Send</button>
            </div>
        </div>
    </div>
</div>
                                            <!-- Replace the file input with a file icon -->
                                            <label for="file-input" class="file-icon-label mx-3 pt-1" role="button">
                                                <iconify-icon icon="akar-icons:attach" width="30px" height="30px"></iconify-icon>
                                            </label>
                                            <input type="file" id="file-input" accept="*" style="display: none;"> <!-- Hidden file input -->
                                            <span id="file-name" class="file-name"></span>

                                            <button id="send-button" class="btn-primary" type="submit">
                                                <span id="loader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                <i class="fa fa-paper-plane" aria-hidden="true"></i> Send
                                            </button>                                        </form>
                                    </div>



                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <!-- chatbox -->


                </div>
            </div>
        </div>


       </div>

@endsection

@section('script')


<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-firestore.js"></script>
<script src="https://cdn.jsdelivr.net/npm/faker@5.5.3/dist/faker.min.js"></script>
<script src="https://cdn.webrtc-experiment.com/RecordRTC.js"></script>
<script src="
https://cdn.jsdelivr.net/npm/audio-converter@1.0.13/index.min.js
"></script>

<script>

jQuery(document).ready(function() {

$(document).on('click',".chat-list a",function() {
    $(".chatbox").addClass('showbox');
    return false;
});

$(document).on('click',".chat-icon",function() {
    $(".chatbox").removeClass('showbox');
});

// Assuming you have an element with class chatbox
const chatboxElement = document.querySelector('.chatbox');

// Attach a click event listener to elements with class chatlist-member
$(document).on('click', '.chatlist-member', function() {

  // Extract chatID from the clicked element's data attribute
  const chatID = $(this).data('chat-id'); // Use data() instead of attr() for data attributes
  const userID = $(this).data('user-id');
  const userToken = $(this).data('user-token');

  // Set the chatID in the input field
  const chatIDInput = $('#chat_id');
  chatIDInput.val(chatID);
  $('#user_id').val(userID);
  $('#user_token').val(userToken);

  chatboxElement.classList.remove('d-none');

  // Load messages for the selected chatID
  loadMessages(chatID);

});


});
const firebaseConfig = {
        apiKey: "AIzaSyAdoPaDTTM6O218Qi0CbR9eZvCQdlmYfq4",
        authDomain: "missplaced-1780b.firebaseapp.com",
        projectId: "missplaced-1780b",
        storageBucket: "missplaced-1780b.appspot.com",
        messagingSenderId: "790746922975",
        appId: "1:790746922975:web:a059d3a40133f3d34f4e6a",
        measurementId: "G-YX9N7VPPV5"
    };

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    const db = firebase.firestore();

    // Dummy user IDs and names (replace with actual data)
    const userId1 = '{{Auth::user()->id}}';
    const userName1 = getRandomUserName();
    const userName2 = getRandomUserName();

    // Dummy post IDs (replace with actual data)
    const postId1 = 'post1';
    const postId2 = 'post2';

    // Function to generate random cartoon avatar link using robohash
    function getRandomAvatar() {
        const avatarSize = 200;
        const userId = Math.floor(Math.random() * 1000);
        const avatarSet = ['set1', 'set2', 'set3'];
        return `https://robohash.org/${userId}?set=${avatarSet[Math.floor(Math.random() * avatarSet.length)]}&size=${avatarSize}x${avatarSize}`;
    }

    // Function to generate a random user name using Faker
    function getRandomUserName() {
        return faker.name.findName();
    }

    // Function to add a message to the chat
async function addMessage(chatId, messageText, fileInput,recording) {
    setTimeout(() => console.log("Third"), 100)

    const chatRef = db.collection('chats').doc(chatId);
    const messagesRef = chatRef.collection('messages');
    var user2 = $('#user_id').val();
    var token = $('#user_token').val();
    var userName = $('#name').text();
    try{


    // Check if a file is selected
    if (fileInput?.files?.length > 0) {
        const file = fileInput.files[0];
        const formData = new FormData();
        formData.append('file', file);

        // Get the CSRF token from the meta tag in your HTML
        const csrfToken = '{{ csrf_token() }}';
        formData.append('_token', csrfToken);

        // Use AJAX to upload the file to your Laravel server
        const response = await $.ajax({
            url: '/upload-file', // Update with your Laravel route
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        });

        const fileURL = response.fileURL;

        // Add the message with the file download URL to Firebase
        await messagesRef.add({
            senderID: parseInt(userId1),
            receiverID: parseInt(user2),
            text: messageText || "",
            fileURL: fileURL || "",
            timestamp: firebase.firestore.Timestamp.now(),
            isRead: false
        });


    }
    else if(recording){
        // Add the message with the file download URL to Firebase
        await messagesRef.add({
            senderID: parseInt(userId1),
            receiverID: parseInt(user2),
            text: messageText || "",
            fileURL: recording || "",
            timestamp: firebase.firestore.Timestamp.now(),
            isRead: false
        });
    }

    else {
        // Add the message without a file
        await messagesRef.add({
            senderID: parseInt(userId1),
            receiverID: parseInt(user2),
            text: messageText || "",
            timestamp: firebase.firestore.Timestamp.now(),
            isRead: false
        });
    }

    //send notification to reciver
    var trancatedText=messageText.length>50?messageText.slice(0,50):messageText;
    sendNotification(token,"New Message from "+ userName,trancatedText)
}
catch(error){
    console.log("Error",error);
}
}

   // Function to load and display all messages based on chat_id
   function loadMessages(chatId) {
    const chatRef = db.collection('chats').doc(chatId);
    const messagesRef = chatRef.collection('messages');

    // Fetch the users array from the chat document
    chatRef.get()
        .then((chatDoc) => {
            const users = chatDoc.exists ? chatDoc.data().users : [];
            var receiver = users.find(user => user.id != '{{Auth::user()->id}}');
            document.getElementById('name').innerHTML = receiver.name;
            document.getElementById('chat-avatar').src = receiver.avatar;

            const messagesContainer = document.querySelector('.message-container');
            messagesContainer.innerHTML = "";

            // Listen for real-time updates to the messages collection
            messagesRef.orderBy('timestamp', 'asc').onSnapshot((snapshot) => {
                // Iterate over the new messages and append them to the container
                snapshot.docChanges().forEach(change => {
                    if (change.type === 'added') {
                        const message = change.doc.data();

                        // Mark the message as read if the receiver is the authenticated user
                        if (message.receiverID === parseInt('{{Auth::user()->id}}')) {
                            messagesRef.doc(change.doc.id).update({
                                isRead: true
                            });
                        }

                        // Find the user details for the sender/receiver
                        const userDetails = users.find(user => user.id === (message.senderID === parseInt('{{Auth::user()->id}}') ? message.senderID : message.receiverID));

                        // Display the message with file URL if available
                        const messageElement = document.createElement('li');
                        if (message.fileURL) {
                            const fileType = getFileType(message.fileURL);
                            if (fileType === 'image') {
                                // Display image within chat
                                messageElement.innerHTML = `
                                    <p>  <img width='300px' src="${message.fileURL}" alt="Image" class="chat-image"/>
                                        <br>
                                        <br>
                                        ${message.text}</p>
                                                                    <span class="time">${formatDate(message.timestamp.toMillis())} </span>

                                `;
                            } else if (fileType === 'video') {
                                // Display video within chat
                                messageElement.innerHTML = `
                                    <p>   <video controls width="300">
                                            <source src="${message.fileURL}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                        <br>
                                            <br>
                                            ${message.text}</p>
                                                                    <span class="time">${formatDate(message.timestamp.toMillis())} </span>

                                `;
                            } else if (fileType === 'audio') {
                                // Display audio within chat
                                messageElement.innerHTML = `
                                    <p>   <audio controls>
                                            <source src="${message.fileURL}" type="audio/mp3">
                                            Your browser does not support the audio tag.
                                        </audio>
                                        <br>
                                            <br>
                                            ${message.text}</p>
                                                                    <span class="time">${formatDate(message.timestamp.toMillis())} </span>

                                `;
                            } else {
                                // Display download link for other file types
                                messageElement.innerHTML = `
                                    <p>     <a href="${message.fileURL}" target="_blank" rel="noopener noreferrer">Download File</a>
                                        <br>
                                            <br>
                                            ${message.text}</p>
                                                                    <span class="time">${formatDate(message.timestamp.toMillis())} </span>

                                `;
                            }
                        } else {
                            // Display text-only message
                            messageElement.innerHTML = `
                                <p>${message.text}</p>
                                <span class="time">${formatDate(message.timestamp.toMillis())} </span>

                            `;
                        }

                        // Add appropriate styling based on sender/receiver
                        messageElement.classList.add(message.senderID === parseInt('{{Auth::user()->id}}') ? 'repaly' : 'sender');

                        messagesContainer.appendChild(messageElement);
                    }
                });
            });
        })
        .catch((error) => {
            console.error('Error fetching chat document:', error);
        });
}



function getFileType(fileURL) {
    const extension = fileURL.split('.').pop().toLowerCase();
    if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
        return 'image';
    } else if (['mp4', 'webm', 'ogg'].includes(extension)) {
        return 'video';
    } else if (['mp3', 'ogg', 'wav'].includes(extension)) {
        return 'audio';
    } else {
        return 'other';
    }
}


// Event listener for file input change
const fileInput = document.getElementById('file-input');
const fileNameSpan = document.getElementById('file-name');

fileInput.addEventListener('change', () => {
    // Update the file name in the UI
    if (fileInput.files.length > 0) {
        const fileName = fileInput.files[0].name;
        fileNameSpan.textContent = fileName;
    } else {
        // Clear the file name if no file is selected
        fileNameSpan.textContent = '';
    }
});

// Handle sending messages
const messageInput = document.getElementById('message-input');
const sendForm = document.getElementById('message-form');
const chatIdInput = document.getElementById('chat_id');
sendForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    const messageText = messageInput.value.trim();
    const chatId = chatIdInput.value.trim();
    const fileInput = document.getElementById('file-input');
    const sendButton = document.getElementById('send-button');
    const loader = document.getElementById('loader');

    // Check if either message text or file is provided
    if (!messageText && !fileInput.files.length) {
        return;
    }

    // Display loader and disable the send button during message sending
    loader.classList.remove('d-none');
    sendButton.disabled = true;

    try {
        // Add the message to the chat, including the file if selected
        await addMessage(chatId, messageText, fileInput,null);

        // Reset the message input and file input
        messageInput.value = '';
        fileInput.value = null;
        fileNameSpan.textContent = '';

        // Hide loader and enable the send button after message is sent successfully
        loader.classList.add('d-none');
        sendButton.disabled = false;
    } catch (error) {
        console.error('Error sending message:', error);

        // Handle error: Hide loader and enable the send button
        loader.classList.add('d-none');
        sendButton.disabled = false;
    }
});




function getAllChatsForUser() {
    var userId = parseInt('{{Auth::user()->id}}');
    const chatsRef = db.collection('chats');
    const chatList = [];

    chatsRef.where('userIds', 'array-contains', userId).onSnapshot((querySnapshot) => {
        const promises = [];

        querySnapshot.forEach((doc) => {
            const userDetails = doc.data().users?.find(user => user.id != userId);
            chatList.push({
                chatId: doc.id,
                userId: userDetails?.id,
                userToken: userDetails?.token,
                avatar: userDetails?.avatar,
                name: userDetails?.name,
                lastMessageTimestamp: 0,
                unreadCount: 0,
                lastMessageText: '',
            });

            // Handle real-time updates for the messages subcollection
            const messagesRef = doc.ref.collection('messages').orderBy('timestamp', 'desc').limit(1);
            messagesRef.onSnapshot((messageSnapshot) => {
                if (!messageSnapshot.empty) {
                    const lastMessage = messageSnapshot.docs[0].data();
                    const lastMessageTimestamp = lastMessage.timestamp;
                    const chatIndex = chatList.findIndex(chat => chat.chatId === doc.id);
                    chatList[chatIndex].lastMessageTimestamp = lastMessageTimestamp.toMillis();
                    chatList[chatIndex].lastMessageText = lastMessage.text || ''; // Store the last message text
                }

                // Fetch the unread messages count for each chat
                const unreadMessagesRef = doc.ref.collection('messages')
                    .where('isRead', '==', false)
                    .where('receiverID', '==', userId);

                // Listen for real-time updates to the unread messages count
                unreadMessagesRef.onSnapshot((unreadMessagesSnapshot) => {
                    const chatIndex = chatList.findIndex(chat => chat.chatId === doc.id);
                    chatList[chatIndex].unreadCount = unreadMessagesSnapshot.size;

                    // Sort the chat list based on the latest message timestamp
                    chatList.sort((a, b) => b.lastMessageTimestamp - a.lastMessageTimestamp);

                    // Display the sorted chat list in the HTML
                    const chatListContainer = document.querySelector('.chat-list');
chatListContainer.innerHTML = '';
chatList.forEach(chat => {
    const unreadBadge = chat.unreadCount > 0 ? `<span class="badge bg-danger">${chat.unreadCount}</span>` : '';
    const truncatedLastMessage = chat.lastMessageText.length > 20 ? `${chat.lastMessageText.slice(0, 20)}...` : chat.lastMessageText;

    chatListContainer.innerHTML += `
        <a href="#" data-chat-id='${chat.chatId}' data-user-id='${chat.userId}' data-user-token='${chat.userToken}' class="chatlist-member d-flex align-items-center">
            <div class="rounded-circle border border-primary">
                <img class="rounded-circle" width='40px' height='40px' src="${chat.avatar}" alt="user img">
            </div>
            <div class="flex-grow-1 ms-3">
                <h3>${chat.name} ${unreadBadge}</h3>
                <p>${truncatedLastMessage}</p> <!-- Display truncated last message text -->
                <small class='text-muted'>${chat.lastMessageTimestamp ? formatDate(chat.lastMessageTimestamp) : ''}</small>
            </div>
        </a>`;
});
                });
            });

            promises.push(messagesRef.get());
        });

        // Wait for all promises to resolve
        Promise.all(promises)
            .catch((error) => {
                console.error('Error getting unread message count:', error);
            });
    });
}


function formatDate(timestamp) {
    const date = new Date(timestamp);
    const options = {
        day: '2-digit',
        month: 'short', // Short month name (e.g., Jan, Feb)
        year: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true, // Use 12-hour clock with AM/PM
    };

    return date.toLocaleDateString('en-US', options);
}





// Call the function to get all chat documents for the user when the page loads
getAllChatsForUser();


    // Load and display all messages based on chat_id when the page loads
    const initialChatId = chatIdInput.value.trim();
    if (initialChatId) {
        loadMessages(initialChatId);
    }


    function sendNotification(token, title, message) {
  const csrfToken = '{{ csrf_token() }}';

  const data = {
    token: token,
    title: title,
    body: message,
  };

  // Append the CSRF token to the data
  data._token = csrfToken;

  $.ajax({
    type: 'POST',
    url: '{{ url("send-notification") }}',
    data: JSON.stringify(data),
    contentType: 'application/json',
    success: function (response) {
      console.log('Success:', response);
    },
    error: function (error) {
      console.error('Error:', error.responseText);
    }
  });
}

document.addEventListener('DOMContentLoaded', function () {
    const micIcon = document.getElementById('mic-icon');
    const recordingModal = new bootstrap.Modal(document.getElementById('recordingModal'));
    const sendRecordingBtn = document.getElementById('send-recording-btn');

    let isRecording = false;
    let mediaRecorder;
    let recordedChunks = [];

    async function startRecording() {
        try {
            // Access the user's microphone
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });

            // Create a media recorder
            mediaRecorder = new RecordRTC(stream, {
                type: 'audio',
                mimeType: 'audio/webm',
            });

            // Start recording
            mediaRecorder.startRecording();
            isRecording = true;
        } catch (error) {
            console.error('Error starting recording:', error);
        }
    }

    function stopRecording() {
        return new Promise((resolve) => {
            // Stop recording
            mediaRecorder.stopRecording(() => {
                isRecording = false;

                // Get the recorded audio data
                mediaRecorder.getDataURL((audioDataURL) => {
                    recordedChunks.push(audioDataURL);
                    resolve();
                });
            });
        });
    }

    micIcon.addEventListener('click', async () => {
        try {
            // Show the recording modal
            recordingModal.show();

            // Start recording
            await startRecording();
        } catch (error) {
            console.error('Error initializing audio recorder:', error);
        }
    });

    sendRecordingBtn.addEventListener('click', async () => {
        if (isRecording) {
            try {
                // Stop recording
                await stopRecording();

                // Convert recorded audio data to MP3
                const mp3Blob = await convertWebMtoMP3(recordedChunks);

                // Send the MP3 audio blob to the server
                const formData = new FormData();
                const csrfToken = '{{ csrf_token() }}';
                formData.append('_token', csrfToken);
                formData.append('file', mp3Blob, 'voice_message.mp3');

                const response = await $.ajax({
                    url: '/upload-file', // Update with your Laravel route
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                });

                const audioFileURL = response.fileURL;
                const chatIdInput = document.getElementById('chat_id');
                const chatId = chatIdInput.value.trim();

                await addMessage(chatId, '', { files: [] }, audioFileURL);

            } catch (error) {
                console.error('Error processing audio:', error);
            } finally {
                // Hide the recording modal
                recordingModal.hide();
            }
        }
    });

    async function convertWebMtoMP3(recordedChunks) {
        return new Promise((resolve, reject) => {
            const webmData = recordedChunks.join('');
            const audioBlob = b64toBlob(webmData.split(',')[1], 'audio/webm');

            // Use audioConverter.js to convert WebM to MP3
            audioConverter(webmData, 'webm', 'mp3', (mp3Data) => {
                const mp3Blob = b64toBlob(mp3Data.split(',')[1], 'audio/mp3');
                resolve(mp3Blob);
            });
        });
    }

    function b64toBlob(b64Data, contentType, sliceSize = 512) {
        const byteCharacters = atob(b64Data);
        const byteArrays = [];
        for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
            const slice = byteCharacters.slice(offset, offset + sliceSize);
            const byteNumbers = new Array(slice.length);
            for (let i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }
            const byteArray = new Uint8Array(byteNumbers);
            byteArrays.push(byteArray);
        }
        const blob = new Blob(byteArrays, { type: contentType });
        return blob;
    }
});

 </script>

@endsection

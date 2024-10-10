
@include('Layout.Layout')

  <div class="chat max-w-xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden mt-8">

    <div class="top bg-blue-600 p-4 flex items-center">
      <img src="https://placehold.co/100" alt="" class="w-10 h-10 rounded-full mr-3">
      <p class="text-white text-lg font-semibold">Chat Room</p>
    </div>

    <div class="messages p-4 h-96 overflow-y-auto">
      @include('chat.recieve', [
          'attachment' => '',
          'message' => '',
      ])
    </div>

    <div class="bottom p-4 border-t">
      <form id="chat-form" action="/broadcast" method="POST" enctype="multipart/form-data" class="flex">
        @csrf
        <input type="text" name="message" id="message" placeholder="Enter Message..." autocomplete="off"
          class="flex-grow border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <!-- Hidden File Input -->
        <input type="file" name="attachment" id="attachment" class="hidden">

        <!-- Label with Icon -->
        <label for="attachment"
          class="ml-2 cursor-pointer border border-gray-300 rounded-lg p-1 text-sm flex items-center">
          <i class="fa fa-paperclip text-gray-600"></i> <!-- Font Awesome Icon -->
        </label>
        <button type="submit"
          class="ml-2 bg-blue-600 text-white rounded-lg px-4 py-2 hover:bg-blue-700 transition duration-200">Send</button>
      </form>
    </div>

  </div>



<script>
  function scrollToBottom() {
    var messageContainer = $('.messages');
    messageContainer.scrollTop(messageContainer[0].scrollHeight);
  }

  const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
    cluster: 'ap1'
  });
  const channel = pusher.subscribe('public');

  // Receive messages
  channel.bind('chat', function(data) {
    $.post("/recieve", {
      _token: '{{ csrf_token() }}',
      message: data.data.message, // Access message from data
      attachment: data.data.attachment // Access attachment from data
    }).done(function(res) {
      // Append the received message to the .messages container
      $(".messages").append(res);
      scrollToBottom(); // Ensure the scroll moves to the bottom after appending
    });
  });

  // Broadcast messages
  $("#chat-form").submit(function(event) {
    event.preventDefault();

    // Create a FormData object to handle file uploads
    var formData = new FormData();
    formData.append('message', $("#message").val());

    // Check if a file is selected and append it to the formData
    if ($('#attachment')[0].files.length > 0) {
      formData.append('attachment', $('#attachment')[0].files[0]);
    }

    $.ajax({
      url: "/broadcast",
      method: "POST",
      headers: {
        'X-Socket-Id': pusher.connection.socket_id,
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      data: formData,
      contentType: false,
      processData: false, // Important: prevent jQuery from processing the data
    }).done(function(res) {
      $(".messages").append(res);
      $("#message").val(''); // Clear the input field
      $('#attachment').val(''); // Clear the file input field
      scrollToBottom();
    });
  });
</script>


</html>

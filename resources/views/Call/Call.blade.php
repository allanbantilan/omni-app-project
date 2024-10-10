<!-- resources/views/phone-dialer.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Phone Dialer</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body class="bg-gray-100 h-screen flex flex-col">
  <!-- Navbar -->
  <nav class="bg-white shadow w-full">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
      <div class="relative flex items-center justify-between h-16">
        <!-- Mobile menu button -->
        <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
          <button type="button"
            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
            aria-controls="mobile-menu" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
              stroke-width="2" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
            <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
              stroke-width="2" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <!-- Logo -->
        <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
          <div class="flex-shrink-0">
            <a href="#" class="text-xl font-bold text-gray-800">Omni Chat</a>
          </div>
          <!-- Desktop Menu -->
          <div class="hidden sm:block sm:ml-6">
            <div class="flex space-x-4">
              <a href="chat"
                class="text-gray-900 hover:bg-gray-200 block px-3 py-2 rounded-md text-base font-medium">Chat</a>
              <a href="send-sms"
                class="text-gray-900 hover:bg-gray-200 block px-3 py-2 rounded-md text-base font-medium">Send SMS</a>
              <a href="#"
                class="text-gray-900 hover:bg-gray-200 block px-3 py-2 rounded-md text-base font-medium">Call</a>
              <a href="#"
                class="text-gray-900 hover:bg-gray-200 block px-3 py-2 rounded-md text-base font-medium">Email</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div class="sm:hidden" id="mobile-menu">
      <div class="px-2 pt-2 pb-3 space-y-1">
        <a href="chat"
          class="text-gray-900 hover:bg-gray-200 block px-3 py-2 rounded-md text-base font-medium">Chat</a>
        <a href="sms-send" class="text-gray-900 hover:bg-gray-200 block px-3 py-2 rounded-md text-base font-medium">Send
          SMS</a>
        <a href="#"
          class="text-gray-900 hover:bg-gray-200 block px-3 py-2 rounded-md text-base font-medium">Call</a>
        <a href="#"
          class="text-gray-900 hover:bg-gray-200 block px-3 py-2 rounded-md text-base font-medium">Email</a>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="flex-grow flex items-center justify-center">
    <div class="max-w-xs mx-auto bg-gray-100 p-4 rounded-3xl shadow-lg">
      <div class="bg-white rounded-2xl p-4 mb-4">
        <input type="text" id="phoneNumber" class="w-full text-2xl text-center focus:outline-none"
          placeholder="Enter phone number" />
      </div>

      <!-- Number Buttons -->
      <div class="grid grid-cols-3 gap-4 mb-4">
        @foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9, '*', 0, '#'] as $num)
          <button onclick="handleNumberClick('{{ $num }}')"
            class="bg-white rounded-full w-16 h-16 flex items-center justify-center text-2xl font-semibold hover:bg-gray-200 transition-colors">
            {{ $num }}
          </button>
        @endforeach
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-between">
        <button
          class="bg-green-500 rounded-full w-16 h-16 flex items-center justify-center text-white hover:bg-green-600 transition-colors"
          onclick="handleCall()">
          <i class="fas fa-phone-alt w-8 h-8 mt-4"></i> <!-- Phone Icon -->
        </button>
        <button
          class="bg-gray-300 rounded-full w-16 h-16 flex items-center justify-center text-gray-700 hover:bg-gray-400 transition-colors"
          onclick="handleDelete()">
          <i class="fas fa-times w-8 h-8 mt-4"></i> <!-- Close/Delete Icon -->
        </button>
        {{-- <button onclick="test()">Test Call</button> --}}

        <h1>09658621772</h1>
      </div>

    </div>
  </div>



  {{-- Incoming Call Modal --}}
  <div class="fixed inset-0 flex items-center justify-center z-50 hidden" id="incomingCallModal">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="bg-white rounded-lg shadow-lg p-6 w-96 z-10">
      <div class="flex justify-between items-center border-b pb-2">
        <h5 class="text-lg font-semibold">Incoming Call</h5>
        <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeModal()">&times;</button>
      </div>
      <div class="mt-4">
        <p id="modalMessage">You have an incoming call!</p>
      </div>
      <div class="mt-4 flex justify-end space-x-2">
        <button type="button" id="rejectCallButton"
          class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400" onclick="rejectCall()">Reject</button>
        <button type="button" id="answerCallButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
          onclick="answerCall()">Answer Call</button>
      </div>
    </div>
  </div>

  {{-- Call Active Modal --}}
  <div id="callActiveModal" class="fixed inset-0 flex items-center justify-center z-[60] hidden">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="bg-white rounded-lg shadow-lg p-6 w-[300px] text-center z-10">
      <h5 class="text-lg font-semibold">Call Active</h5>
      <p id="callTimer" class="text-xl mt-4">00:00</p>
      <button type="button" onclick="endCall()"
        class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">End Call</button>
    </div>
  </div>

  <script src="/js/twilio.min.js"></script>
  <script>
    let timerInterval;
    let seconds = 0;
    let device;

    // Function to handle incoming phone call
    function handleIncomingPhoneCall(call) {
      document.getElementById('incomingCallModal').classList.remove('hidden');

      // When answer button is clicked
      document.getElementById('answerCallButton').addEventListener('click', function() {
        answerCall(call);
      });

      // When reject button is clicked
      document.getElementById('rejectCallButton').addEventListener('click', function() {
        rejectCall(call);
      });
    }

    function closeModal() {
      document.getElementById('incomingCallModal').classList.add('hidden');
    }

    // Answer the call and show active call modal
    function answerCall(call) {
      console.log("Call answered");
      call.accept(); // Accept the Twilio call
      closeModal(); // Hide the incoming call modal
      startTimer(); // Start the call timer
      document.getElementById('callActiveModal').classList.remove('hidden'); // Show call active modal
    }

    // Reject the call
    function rejectCall(call) {
      console.log("Call rejected");
      call.reject(); // Reject the Twilio call
      closeModal(); // Hide the modal
    }

    // Start the call timer
    function startTimer() {
      timerInterval = setInterval(() => {
        seconds++;
        const minutes = Math.floor(seconds / 60);
        const secs = seconds % 60;
        document.getElementById('callTimer').innerText =
          `${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
      }, 1000);
    }

    // End the call and hide active call modal
    function endCall() {
      console.log("Call ended");
      clearInterval(timerInterval); // Stop the timer
      seconds = 0; // Reset the timer
      document.getElementById('callActiveModal').classList.add('hidden'); // Hide active call modal
    }

    // Twilio Access Token and Device initialization
    async function getTwilioAccessToken() {
      console.log("Requesting access token for Twilio...");
      try {
        const response = await fetch("/phone/access-token");
        const data = await response.json();

        token = data.token;
        identity = data.identity;

        initializeDevice();
      } catch (error) {
        console.log(error);
      }
    }

    function initializeDevice() {
      device = new Twilio.Device(token, {
        logLevel: 1,
        codecPreferences: ['opus', 'pcmu'],
        maxCallSignalingTimeoutMs: 30000
      });

      device.register();
      device.on("incoming", handleIncomingPhoneCall);
    }

    window.onload = getTwilioAccessToken;
  </script>
  <script>
    let phoneNumber = "";

    function handleNumberClick(num) {
      phoneNumber += num;
      document.getElementById("phoneNumber").value = phoneNumber;
    }

    function handleDelete() {
      phoneNumber = phoneNumber.slice(0, -1);
      document.getElementById("phoneNumber").value = phoneNumber;
    }

    function handleCall() {
      let phoneNumberInput = document.getElementById("phoneNumber").value;

      if (phoneNumberInput.startsWith('0')) {
        phoneNumberInput = '+63' + phoneNumberInput.slice(1);
      }

      if (phoneNumberInput.length > 0) {
        fetch('/make-call', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
              phone_number: phoneNumberInput
            })
          })
          .then(response => response.json())
          .then(data => {
            alert(data.message);
          })
          .catch(error => {
            console.error('Error:', error);
          });
      } else {
        alert('Enter a phone number first.');
      }
    }
  </script>
</body>

</html>

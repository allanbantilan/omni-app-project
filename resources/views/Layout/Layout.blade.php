<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  @vite('resources/css/app.css')
  <link rel="stylesheet" href="../css/style.css">
  {{-- <link rel="stylesheet" href="/style.css"> --}}
  <title>Omni Chat</title>
</head>


<body>
  <nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
      <div class="relative flex items-center justify-between h-16">
        <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
          <!-- Mobile menu button-->
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
        <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
          <div class="flex-shrink-0">
            <a href="#" class="text-xl font-bold text-gray-800">Omni Chat</a>
          </div>
          <div class="hidden sm:block sm:ml-6">
            <div class="flex space-x-4">
              <a href="chat"
                class="text-gray-900 hover:bg-gray-200 block px-3 py-2 rounded-md text-base font-medium">Chat</a>

              <a href="call"
                class="text-gray-900 hover:bg-gray-200 block px-3 py-2 rounded-md text-base font-medium">Call</a>
              <a href="send-sms"
                class="text-gray-900 hover:bg-gray-200 block px-3 py-2 rounded-md text-base font-medium">Send
                SMS</a>
              <a href="{{ route('send.email.form') }}"
                class="text-gray-900 hover:bg-gray-200 block px-3 py-2 rounded-md text-base font-medium">Email</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile Menu, show/hide based on menu state. -->
    <div class="sm:hidden" id="mobile-menu">
      <div class="px-2 pt-2 pb-3 space-y-1">
        <a href="chat"
          class="text-gray-900 hover:bg-gray-200 block px-3 py-2 rounded-md text-base font-medium">Chat</a>

        <a href="call"
          class="text-gray-900 hover:bg-gray-200 block px-3 py-2 rounded-md text-base font-medium">Call</a>
        <a href="sms-send" class="text-gray-900 hover:bg-gray-200 block px-3 py-2 rounded-md text-base font-medium">Send
          SMS</a>
        <a href="send-email"
          class="text-gray-900 hover:bg-gray-200 block px-3 py-2 rounded-md text-base font-medium">Email</a>
      </div>
    </div>
  </nav>
  <slot />
</body>
<!-- Include compiled JS -->
@vite('resources/js/app.js')

</html>

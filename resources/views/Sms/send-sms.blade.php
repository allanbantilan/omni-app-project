@include('Layout.Layout')
<div class="container mx-auto mt-10 p-8">
  <div class="bg-gray-200 rounded-lg shadow-md p-5 flex">
    <!-- Form Section -->
    <div class="w-full md:w-1/2 pr-4">
      <h1 class="text-3xl font-bold mb-4">Send SMS</h1>

      @if (session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
      @endif

      <form action="{{ route('send.sms') }}" method="POST" class="mb-4">
        @csrf
        <div class="mb-4">
          <label for="to" class="block text-sm font-medium text-gray-700">Recipient Phone Number:</label>
          <input type="text" name="to" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
        </div>
        <div class="mb-4">
          <label for="message" class="block text-sm font-medium text-gray-700">Message:</label>
          <textarea name="message" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required></textarea>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Send SMS</button>
      </form>

      <!-- Display Submitted Data -->
      @if (old('to') || old('message'))
        <div class="mt-6">
          <h2 class="text-xl font-semibold">Submitted SMS</h2>
          <ul class="list-disc pl-5">
            <li><strong>Recipient Phone Number:</strong> {{ old('to') }}</li>
            <li><strong>Message:</strong> {{ old('message') }}</li>
          </ul>
        </div>
      @endif
    </div>

    <!-- Inbox Section -->
    <div class="w-full md:w-1/2 pl-4">
      <!-- Include Inbox -->
      @include('Sms.Inbox', ['messages' => $messages]) <!-- Pass messages to the inbox -->
    </div>
  </div>
</div>
@include('layout.layout')


<div class="container mx-auto p-8">
  <div class="flex items-center justify-between p-4 bg-white shadow-md rounded-lg mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Send Email</h1>
    <a href="inbox" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">
      Go to Inbox
    </a>
  </div>

  @if (session('success'))
    <div class="bg-green-500 text-white p-4 mb-4 rounded">
      {{ session('success') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="bg-red-500 text-white p-4 mb-4 rounded">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('send.email') }}" method="POST" enctype="multipart/form-data"
    class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    @csrf

    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2" for="to">To:</label>
      <input type="email" name="to" required
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        placeholder="recipient@example.com">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2" for="cc">CC:</label>
      <input type="email" name="cc"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        placeholder="cc@example.com">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2" for="subject">Subject:</label>
      <input type="text" name="subject" required
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>

    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2" for="message">Message:</label>
      <textarea name="message" required
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
    </div>

    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2" for="attachment">Attachment:</label>
      <input type="file" name="attachment"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>

    <button type="submit"
      class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Send
      Email</button>
  </form>
</div>

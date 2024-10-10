

<h2 class="text-xl font-semibold mt-6 mb-2">Inbox</h2>
@if ($messages->count() > 0)
  <ul class="list-disc pl-5">
    @foreach ($messages as $message)
      <li class="bg-gray-50 border border-gray-200 rounded-md p-3 mb-2">
        <strong>From:</strong> {{ $message->from }}<br>
        <strong>Message:</strong> {{ $message->body }}<br>
        <small class="text-gray-500">{{ $message->created_at }}</small>
      </li>
    @endforeach
  </ul>
@else
  <p>No messages received yet.</p>
@endif

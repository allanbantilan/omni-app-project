<div class="left message flex items-start mb-4">
  <img src="https://placehold.co/100" alt="avatar" class="w-10 h-10 rounded-full mr-3">
  <div>
    <p class="bg-gray-200 text-gray-800 p-2 rounded-lg shadow-md">{{ $message }}</p>
    @if ($attachment)
      <p class="mt-2">
        <a href="{{ asset('storage/' . $attachment) }}" target="_blank" class="text-blue-500 underline">View Attachment</a>
      </p>
    @endif
  </div>
</div>
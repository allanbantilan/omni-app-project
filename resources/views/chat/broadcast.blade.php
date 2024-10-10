<div class="right message flex items-start mb-4 justify-end">
  <div>
    <p class="bg-blue-600 text-white p-2 rounded-lg shadow-md">{{ $message }}</p>
    @if ($attachment)
      <p class="mt-2"><a href="{{ asset('storage/' . $attachment) }}" target="_blank" class="text-blue-500 underline">View
          Attachment</a></p>
    @endif
  </div>
  <img src="https://placehold.co/100" alt="avatar" class="w-10 h-10 rounded-full ml-3">
</div>

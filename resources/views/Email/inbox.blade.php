@include('layout.layout')

<div class="container mx-auto p-8">
  <div class="flex items-center justify-between p-4 bg-white shadow-md rounded-lg mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Inbox</h1>
    <a href="{{ route('send.email.form') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">
      Compose Email
    </a>
  </div>

  @if ($emails && count($emails) > 0)
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <div class="border-b">
        <div class="flex justify-between items-center p-4">
          <h2 class="text-2xl font-bold">Your Emails</h2>
        </div>
      </div>

      @php
          // Array to track unique email subjects
          $uniqueEmails = [];
      @endphp

      @foreach ($emails as $email)
        {{-- Avoid duplicate email subjects --}}
        @if (!in_array($email['subject'], $uniqueEmails))
          @php
              // Track the subject to ensure uniqueness
              $uniqueEmails[] = $email['subject'];
          @endphp

          <div class="p-4 border-b hover:bg-gray-100 transition duration-200 ease-in-out">
            <div class="flex justify-between items-start">
              <h3 class="text-lg font-medium text-gray-800">SUBJECT: {{ $email['subject'] }}</h3>
              <span class="text-xs text-gray-400">{{ ($email['date']) }}</span>
            </div>
            <div class="mt-1">
              <p class="text-sm text-gray-600">From: {{ $email['from'] }}</p>
              <p class="text-sm text-gray-500">{{ $email['snippet'] }}</p>
            </div>

            {{-- Display Attachments --}}
            @if (!empty($email['attachments']))
              <h3 class="font-semibold mt-2 text-gray-700">Attachments:</h3>
              <ul class="list-disc pl-5">
                @foreach ($email['attachments'] as $attachment)
                  <li>
                    <a href="data:application/octet-stream;base64,{{ $attachment['data'] }}"
                       download="{{ $attachment['filename'] }}"
                       class="text-blue-500 hover:underline">{{ $attachment['filename'] }}</a>
                  </li>
                @endforeach
              </ul>
            @endif

            {{-- Display Reply Chain --}}
            @if (!empty($email['replyChain']))
              <h3 class="font-semibold mt-2 text-gray-700">Replies:</h3>
              @foreach ($email['replyChain'] as $reply)
                @if ($reply['from'] !== $email['from']) {{-- Ensure reply is not from the original sender --}}
                  <div class="border-l-2 border-gray-300 pl-4 mt-2">
                    <p class="text-sm text-gray-600">From: {{ $reply['from'] }}</p>
                    <span class="text-xs text-gray-400">
                      {{ \Carbon\Carbon::parse($reply['date'])->format('M d, Y h:i A') }}
                    </span>
                    <p class="text-sm">{{ $reply['snippet'] }}</p>

                    {{-- Display Reply Attachments --}}
                    @if (!empty($reply['attachments']))
                      <h4 class="font-semibold mt-2 text-gray-700">Attachments:</h4>
                      <ul class="list-disc pl-5">
                        @foreach ($reply['attachments'] as $attachment)
                          <li>
                            <a href="data:application/octet-stream;base64,{{ $attachment['data'] }}"
                               download="{{ $attachment['filename'] }}"
                               class="text-blue-500 hover:underline">{{ $attachment['filename'] }}</a>
                          </li>
                        @endforeach
                      </ul>
                    @endif
                  </div>
                @endif
              @endforeach
            @endif
          </div>
        @endif
      @endforeach
    </div>
  @else
    <p>No emails found.</p>
  @endif
</div>

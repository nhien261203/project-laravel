<div class="flex items-start justify-end mb-4 message">
  <p class="inline-flex text-sm text-gray-800 bg-gray-200 rounded-md px-4 py-2 m-1">
    {{ $message }}
  </p>
</div>

<script>
  console.log("🔵 Rendered BROADCAST view:", @json($message));
</script>

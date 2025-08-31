<div class="flex items-start mb-4 message">
  <p class="inline-flex text-sm text-white bg-blue-500 rounded-md px-4 py-2 m-1">
    {{ $message }}
  </p>
</div>

<script>
  console.log("🟢 Rendered RECEIVE view:", @json($message));
</script>

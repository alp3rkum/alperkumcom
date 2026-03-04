<!-- Overlay -->
<div id="overlay" class="fixed inset-0 bg-gray-950/60 flex items-center justify-center opacity-0 invisible transition-opacity duration-300 z-50">
  <!-- Spinner -->
  <svg class="animate-spin h-16 w-16" viewBox="0 0 50 50">
    <defs>
      <radialGradient id="radGrad" cx="50%" cy="50%" r="52%">
        <stop offset="0%" stop-color="rgb(136, 181, 245)" />
        <stop offset="80%" stop-color="rgb(136, 181, 245)" />
        <stop offset="100%" stop-color="rgb(31,41,55)" />
      </radialGradient>
    </defs>
    <circle 
      cx="25" cy="25" r="20" 
      stroke="url(#radGrad)" 
      stroke-width="5" 
      fill="none" 
      stroke-linecap="round"
      stroke-dasharray="90 60"
      stroke-dashoffset="0"
    />
  </svg>
</div>
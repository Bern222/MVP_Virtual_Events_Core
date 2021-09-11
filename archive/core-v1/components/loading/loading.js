const loadingOverlay = document.querySelector('.loading');
function toggleLoading(event){
  if (event.keyCode !== 13) return;
  
  document.activeElement.blur();
  
  if (loadingOverlay.classList.contains('hidden')){
    loadingOverlay.classList.remove('hidden');
  } else {
    loadingOverlay.classList.add('hidden');
  }
}
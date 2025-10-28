document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', () => {
      const li = button.closest('li');
      const nameSpan = li.querySelector('.category-name');
      const editForm = li.querySelector('.edit-form');

      // Oculta el nombre y muestra el formulario de edición
      nameSpan.classList.add('hidden');
      editForm.classList.remove('hidden');

      // Foco directo al input
      editForm.querySelector('input[name="name"]').focus();
    });
  });
});
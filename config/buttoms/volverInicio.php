<?php
    // antes Christian tenía: return '<a href="/tareas"
    // pero al ponerlo con rutas relativas de constantes como "<?= BASE_URL ? >/home" (o /tareas, eso no es lo importante ahora)
    // el botón en cuestión no funcionaba bien, por lo que decidí incrustar el botón directamente en la vista. 
    // Repetimos código? Sí. Pero funciona y de momento es la solución más rápida, porque no quiero entretenerme en entender cómo hacerlo funcionar.
    return '<a href="<?= BASE_URL ?>/home" 
        class="bg-gray-800 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-gray-700">
        Volver a inicio
        </a>';
?>
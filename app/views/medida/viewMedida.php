<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CopiGaby — Gestión de Unidades de Medida</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Pacifico&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/dist.css">
  <link rel="stylesheet" href="assets/css/theme.css">
  <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body class="font-['Nunito'] bg-[#f0f2f7] text-[#1f2937] min-h-screen flex">

  <?php include 'app/views/layouts/viewMenuLateral.php'; ?>

  <div class="ml-[260px] flex-1 flex flex-col min-h-screen">
    <header class="h-16 bg-white border-b-2 border-orange flex items-center justify-between px-8 sticky top-0 z-50 shadow-[0_2px_12px_rgba(0,0,0,0.06)]">
      <h2 class="text-navy-dark font-extrabold text-xl uppercase tracking-tighter">Gestión de Unidades de Medida</h2>
      <div class="flex items-center">
        <?php include 'app/views/layouts/viewUsuarioHeader.php'; ?>
      </div>
    </header>

    <main class="p-8 flex-1 animate-fade-up">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-2xl font-[900] text-gray-800">Unidades de Medida</h1>
          <p class="text-gray-500 text-sm font-semibold">Configuración de las unidades de los productos</p>
        </div>
        <button id="btnOpenModal" class="flex items-center gap-2 bg-gradient-to-r from-orange to-orange-dk text-navy-dark font-black px-6 py-3 rounded-xl shadow-lg hover:-translate-y-1 transition-all">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
          AÑADIR UNIDAD DE MEDIDA
        </button>
      </div>

      <div class="bg-white rounded-custom shadow-sm overflow-hidden border border-gray-100">
        <table class="w-full text-left">
<thead class="bg-navy-dark text-white text-[0.7rem] uppercase tracking-widest">
              <tr>
                <th class="px-6 py-4">Código</th>
                <th class="px-6 py-4">Nombre</th>
                <th class="px-6 py-4">Abreviatura</th>
                <th class="px-6 py-4">Unidades por Caja</th>
                <th class="px-6 py-4">Estado</th>
                <th class="px-6 py-4 text-center">Consultar</th>
              </tr>
            </thead>
            <tbody class="text-gray-700 font-semibold text-sm divide-y">
              <?php foreach ($medidas as $m): ?>
                <tr class="hover:bg-gray-50/80 transition-colors">
                  <td class="px-6 py-4 font-black text-navy-dark">#<?= str_pad($m['codigo_media'], 3, '0', STR_PAD_LEFT) ?></td>
                  <td class="px-6 py-4 uppercase"><?= htmlspecialchars($m['nombre']) ?></td>
                  <td class="px-6 py-4 font-bold text-navy-light"><?= htmlspecialchars($m['abreviatura'] ?? '—') ?></td>
                  <td class="px-6 py-4 text-center font-bold"><?= (int) ($m['cantidad_unidad'] ?? 1) ?></td>
                  <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-[0.65rem] font-black uppercase <?= $m['estado'] ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' ?>">
                      <?= $m['estado'] ? 'Activo' : 'Inactivo' ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-center">
                    <button type="button" onclick='viewDetails(<?= json_encode($m, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' class="group relative text-orange-dk p-2 hover:bg-orange/10 rounded-lg transition-colors">
                      <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block bg-navy-dark text-white text-[10px] px-2 py-1 rounded whitespace-nowrap pointer-events-none z-10 shadow-lg">Ver Detalle</span>
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
        </table>
      </div>
    </main>
  </div>

  <div id="modalMedida" class="fixed inset-0 z-[150] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
      <div class="fixed inset-0 bg-navy-dark/60 backdrop-blur-sm" onclick="toggleModal()"></div>
      <div class="relative bg-white shadow-xl rounded-custom w-full max-w-md animate-fade-up overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50/50 flex justify-between items-center">
          <h3 class="text-xl font-black text-navy-dark">Nueva Unidad de Medida</h3>
          <button onclick="toggleModal()" class="text-gray-400 hover:text-navy-dark"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <form action="?url=medida&type=register" method="POST" class="p-6">
          <div class="mb-4">
             <label class="block text-xs font-black text-gray-400 uppercase mb-1">Nombre (ej: Kilos, Litros, Unidad) <span class="text-red-500">*</span></label>
             <input type="text" name="nombre" required class="w-full px-4 py-2 bg-gray-50 border rounded-lg focus:border-orange outline-none font-bold text-sm" placeholder="Kilos">
          </div>
          <div class="mb-4">
            <label class="block text-xs font-black text-gray-400 uppercase mb-1">Abreviatura</label>
             <input type="text" name="abreviatura" maxlength="10" class="w-full px-4 py-2 bg-gray-50 border rounded-lg focus:border-orange outline-none font-bold text-sm" placeholder="Kg">
          </div>
          <div class="mb-4">
             <label class="block text-xs font-black text-gray-400 uppercase mb-1">Unidades por Caja <span class="text-xs text-gray-400 font-normal">(ej: 12 para docena, 1 para unidad suelta)</span></label>
             <input type="number" name="cantidad_unidad" min="1" value="1" required class="w-full px-4 py-2 bg-gray-50 border rounded-lg focus:border-orange outline-none font-bold text-sm">
          </div>
          <div class="flex justify-end gap-3 mt-8">
            <button type="button" onclick="toggleModal()" class="px-6 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-lg">Cancelar</button>
            <button type="submit" class="px-8 py-2 text-sm font-black bg-navy-dark text-white rounded-lg hover:bg-navy shadow-lg transition-all">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="modalEditMedida" class="fixed inset-0 z-[150] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
      <div class="fixed inset-0 bg-navy-dark/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
      <div class="relative bg-white shadow-xl rounded-custom w-full max-w-md animate-fade-up overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50/50 flex justify-between items-center">
          <h3 class="text-xl font-black text-navy-dark">Editar Unidad de Medida</h3>
          <button onclick="closeEditModal()" class="text-gray-400 hover:text-navy-dark"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <form action="?url=medida&type=update" method="POST" class="p-6">
          <input type="hidden" name="codigo_media" id="edit_codigo">
          <div class="mb-4">
             <label class="block text-xs font-black text-gray-400 uppercase mb-1">Nombre <span class="text-red-500">*</span></label>
             <input type="text" name="nombre" id="edit_nombre" required class="w-full px-4 py-2 bg-gray-50 border rounded-lg focus:border-orange outline-none font-bold text-sm" placeholder="Kilos">
          </div>
          <div class="mb-4">
            <label class="block text-xs font-black text-gray-400 uppercase mb-1">Abreviatura</label>
             <input type="text" name="abreviatura" id="edit_abreviatura" maxlength="10" class="w-full px-4 py-2 bg-gray-50 border rounded-lg focus:border-orange outline-none font-bold text-sm" placeholder="Kg">
          </div>
          <div class="mb-4">
             <label class="block text-xs font-black text-gray-400 uppercase mb-1">Unidades por Caja</label>
             <input type="number" name="cantidad_unidad" id="edit_cantidad_unidad" min="1" value="1" required class="w-full px-4 py-2 bg-gray-50 border rounded-lg focus:border-orange outline-none font-bold text-sm">
          </div>
          <div class="flex items-center gap-2 mb-4">
              <input type="checkbox" name="estado" id="edit_estado" class="w-4 h-4 accent-orange">
              <label class="text-sm font-bold text-navy-dark uppercase tracking-tight">Activo</label>
          </div>
          <div class="flex justify-end gap-3 mt-8">
            <button type="button" onclick="closeEditModal()" class="px-6 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-lg">Cancelar</button>
            <button type="submit" class="px-8 py-2 text-sm font-black bg-navy-dark text-white rounded-lg hover:bg-navy shadow-lg transition-all">Actualizar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Ver Detalle -->
  <div id="modalDetalle" class="fixed inset-0 z-[160] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
      <div class="fixed inset-0 bg-navy-dark/80 backdrop-blur-md" onclick="closeDetalleModal()"></div>
      <div class="relative bg-white shadow-2xl rounded-custom w-full max-w-md animate-fade-up overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50/50 flex justify-between items-center">
          <h3 class="text-xl font-black text-navy-dark">Detalle de Unidad</h3>
          <button type="button" onclick="closeDetalleModal()" class="text-gray-400 hover:text-navy-dark"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div class="p-6 space-y-4">
          <div><p class="text-[0.65rem] font-black text-gray-400 uppercase mb-1">Código</p><p id="det_codigo" class="font-black text-navy-dark">—</p></div>
          <div><p class="text-[0.65rem] font-black text-gray-400 uppercase mb-1">Nombre</p><p id="det_nombre" class="font-bold uppercase">—</p></div>
          <div><p class="text-[0.65rem] font-black text-gray-400 uppercase mb-1">Abreviatura</p><p id="det_abreviatura" class="font-bold text-navy-light">—</p></div>
          <div><p class="text-[0.65rem] font-black text-gray-400 uppercase mb-1">Unidades por Caja</p><p id="det_cantidad_unidad" class="font-bold text-navy-light">1</p></div>
          <div><p class="text-[0.65rem] font-black text-gray-400 uppercase mb-1">Estado</p><p id="det_estado" class="font-bold">—</p></div>
        </div>
        <div class="modal-footer bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t">
          <button type="button" id="btnDetalleEliminar" class="px-5 py-2 text-sm font-black text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">Eliminar</button>
          <button type="button" id="btnDetalleEditar" class="px-5 py-2 text-sm font-black text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">Modificar</button>
          <button type="button" onclick="closeDetalleModal()" class="px-6 py-2 text-sm font-black bg-navy-dark text-white rounded-lg hover:bg-navy transition-all">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const modal = document.getElementById('modalMedida');
    const btnOpen = document.getElementById('btnOpenModal');
    const toggleModal = () => modal.classList.toggle('hidden');
    btnOpen.onclick = toggleModal;

    let currentRecord = null;

    function viewDetails(data) {
      currentRecord = data;
      document.getElementById('det_codigo').textContent = '#' + String(data.codigo_media).padStart(3, '0');
      document.getElementById('det_nombre').textContent = data.nombre;
      document.getElementById('det_abreviatura').textContent = data.abreviatura || '—';
      document.getElementById('det_cantidad_unidad').textContent = (data.cantidad_unidad ?? 1);
      document.getElementById('det_estado').textContent = data.estado == 1 ? 'Activo' : 'Inactivo';
      document.getElementById('btnDetalleEditar').onclick = () => { closeDetalleModal(); openEditModal(currentRecord); };
      document.getElementById('btnDetalleEliminar').onclick = () => confirmDelete(currentRecord.codigo_media);
      document.getElementById('modalDetalle').classList.remove('hidden');
    }

    function closeDetalleModal() {
      document.getElementById('modalDetalle').classList.add('hidden');
    }

    function openEditModal(data) {
        document.getElementById('edit_codigo').value = data.codigo_media;
        document.getElementById('edit_nombre').value = data.nombre;
        document.getElementById('edit_abreviatura').value = data.abreviatura || '';
        document.getElementById('edit_cantidad_unidad').value = data.cantidad_unidad ?? 1;
        document.getElementById('edit_estado').checked = (data.estado == 1);
        document.getElementById('modalEditMedida').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modalEditMedida').classList.add('hidden');
    }

    function confirmDelete(id) {
      if(confirm('¿Desea desactivar esta unidad de medida?')) {
        const f = new FormData();
        f.append('deleteMedida', 'true');
        f.append('codigo_media', id);
        fetch('?url=medida&type=main', { method:'POST', body:f })
        .then(r => r.json()).then(d => {
          if(d.status === 'success') location.reload();
          else alert('No se pudo procesar');
        });
      }
    }
  </script>
</body>
</html>

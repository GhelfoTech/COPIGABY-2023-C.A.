<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CopiGaby — Gestión de Clientes</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Pacifico&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/dist.css">
  <link rel="stylesheet" href="assets/css/theme.css">
  <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body class="font-[Nunito] bg-[#f0f2f7] text-[#1f2937] min-h-screen flex">

  <?php include 'app/views/layouts/viewMenuLateral.php'; ?>

  <div class="ml-[260px] flex-1 flex flex-col min-h-screen">
    <header class="h-16 bg-white border-b-2 border-orange flex items-center justify-between px-8 sticky top-0 z-50 shadow-[0_2px_12px_rgba(0,0,0,0.06)]">
      <h2 class="text-navy-dark font-extrabold text-xl uppercase tracking-tighter">Directorio de Clientes</h2>
      <div class="flex items-center">
        <?php include 'app/views/layouts/viewUsuarioHeader.php'; ?>
      </div>
    </header>

    <main class="p-8 flex-1 animate-fade-up">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-2xl font-black text-gray-800">Clientes</h1>
          <p class="text-gray-500 text-sm font-semibold">Administración de datos de contacto para facturación</p>
        </div>
        <button id="btnOpenModal" class="flex items-center gap-2 bg-linear-to-r from-orange to-orange-dk text-navy-dark font-black px-6 py-3 rounded-xl shadow-lg hover:-translate-y-1 transition-all">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
          AÑADIR CLIENTE
        </button>
      </div>

      <div class="bg-white rounded-[14px] shadow-sm overflow-hidden border border-gray-100">
        <table class="w-full text-left">
            <thead class="bg-navy-dark text-white text-[0.7rem] uppercase tracking-widest">
            <tr>
              <th class="px-6 py-4">Cédula</th>
              <th class="px-6 py-4">Nombre Completo</th>
              <th class="px-6 py-4">Teléfono</th>
              <th class="px-6 py-4">Correo</th>
              <th class="px-6 py-4">Estado</th>
              <th class="px-6 py-4 text-center">Consultar</th>
            </tr>
          </thead>
          <tbody class="text-gray-700 font-semibold text-sm divide-y">
            <?php if (!empty($clientes)): foreach ($clientes as $c): ?>
              <tr class="hover:bg-gray-50/80 transition-colors">
                <td class="px-6 py-4 font-black text-navy-dark"><?= $c['cedula_cliente'] ?></td>
                <td class="px-6 py-4 uppercase font-bold text-navy-light"><?= htmlspecialchars($c['nombre']) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($c['telefono']) ?></td>
               <td class="px-6 py-4 text-gray-500"><?= htmlspecialchars($c['correo']) ?></td>
                 <td class="px-6 py-4">
                   <span class="px-3 py-1 rounded-full text-[0.65rem] font-black uppercase <?= ($c['estado'] ?? 1) ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' ?>">
                     <?= ($c['estado'] ?? 1) ? 'Activo' : 'Inactivo' ?>
                   </span>
                 </td>
                 <td class="px-6 py-4 text-center">
                  <button type="button" onclick='viewDetails(<?= json_encode($c, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' class="group relative text-orange-dk p-2 hover:bg-orange/10 rounded-lg transition-colors">
                    <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block bg-navy-dark text-white text-[10px] px-2 py-1 rounded whitespace-nowrap pointer-events-none z-10 shadow-lg">Consultar</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </button>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400 font-bold italic">No hay clientes registrados.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <div id="modalCliente" class="fixed inset-0 z-150 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
      <div class="fixed inset-0 bg-navy-dark/60 backdrop-blur-sm" onclick="toggleModal()"></div>
      <div class="relative bg-white shadow-xl rounded-[14px] w-full max-w-md animate-fade-up overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50/50 flex justify-between items-center">
          <h3 class="text-xl font-black text-navy-dark">Nuevo Cliente</h3>
          <button onclick="toggleModal()" class="text-gray-400 hover:text-navy-dark"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
         <form action="?url=cliente&type=register" method="POST" class="p-6">
           <div class="mb-4">
              <label class="block text-xs font-black text-gray-400 uppercase mb-1">Cédula <span class="text-red-500">*</span></label>
              <div class="flex gap-0">
                <span class="inline-flex items-center px-3 py-2 bg-gray-200 border border-r-0 rounded-l-lg text-sm font-black text-navy-dark">V-</span>
                 <input type="tel" inputmode="numeric" pattern="[0-9]*" name="cedula_cliente" required class="w-full px-4 py-2 bg-gray-50 border rounded-r-lg focus:border-orange outline-hidden font-bold no-spinner text-sm" placeholder="12345678">
              </div>
           </div>
          <div class="mb-4">
             <label class="block text-xs font-black text-gray-400 uppercase mb-1">Nombre Completo <span class="text-red-500">*</span></label>
              <input type="text" name="nombre" required class="w-full px-4 py-2 bg-gray-50 border rounded-lg focus:border-orange outline-hidden font-bold text-sm" placeholder="Juan Pérez">
          </div>
          <div class="mb-4">
             <label class="block text-xs font-black text-gray-400 uppercase mb-1">Teléfono <span class="text-red-500">*</span></label>
              <input type="tel" inputmode="numeric" pattern="[0-9]*" name="telefono" required class="w-full px-4 py-2 bg-gray-50 border rounded-lg focus:border-orange outline-hidden font-bold text-sm" placeholder="04121234567">
          </div>
          <div class="mb-4">
            <label class="block text-xs font-black text-gray-400 uppercase mb-1">Correo Electrónico</label>
             <input type="email" name="correo" class="w-full px-4 py-2 bg-gray-50 border rounded-lg focus:border-orange outline-hidden font-bold text-sm" placeholder="correo@cliente.com">
          </div>
          <div class="mb-4">
            <label class="block text-xs font-black text-gray-400 uppercase mb-1">Dirección</label>
            <textarea name="direccion" rows="2" class="w-full px-4 py-2 bg-gray-50 border rounded-lg focus:border-orange outline-hidden font-bold"></textarea>
          </div>
          <div class="flex justify-end gap-3 mt-8">
            <button type="button" onclick="toggleModal()" class="px-6 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-lg">Cancelar</button>
            <button type="submit" class="px-8 py-2 text-sm font-black bg-navy-dark text-white rounded-lg hover:bg-navy shadow-lg transition-all">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="modalEditCliente" class="fixed inset-0 z-150 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
      <div class="fixed inset-0 bg-navy-dark/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
      <div class="relative bg-white shadow-xl rounded-[14px] w-full max-w-md animate-fade-up overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50/50 flex justify-between items-center">
          <h3 class="text-xl font-black text-navy-dark">Editar Cliente</h3>
          <button onclick="closeEditModal()" class="text-gray-400 hover:text-navy-dark"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <form action="?url=cliente&type=update" method="POST" class="p-6">
          <input type="hidden" name="id_actual" id="edit_id_actual">
           <div class="mb-4">
             <label class="block text-xs font-black text-gray-400 uppercase mb-1">Cédula</label>
             <div class="flex gap-0">
               <span class="inline-flex items-center px-3 py-2 bg-gray-200 border border-r-0 rounded-l-lg text-sm font-black text-navy-dark">V-</span>
               <input type="tel" inputmode="numeric" pattern="[0-9]*" name="cedula_cliente" id="edit_cedula" required class="w-full px-4 py-2 bg-gray-50 border rounded-r-lg focus:border-orange outline-hidden font-bold no-spinner">
             </div>
           </div>
          <div class="mb-4">
             <label class="block text-xs font-black text-gray-400 uppercase mb-1">Nombre Completo <span class="text-red-500">*</span></label>
             <input type="text" name="nombre" id="edit_nombre" required class="w-full px-4 py-2 bg-gray-50 border rounded-lg focus:border-orange outline-hidden font-bold">
          </div>
          <div class="mb-4">
             <label class="block text-xs font-black text-gray-400 uppercase mb-1">Teléfono <span class="text-red-500">*</span></label>
             <input type="tel" inputmode="numeric" pattern="[0-9]*" name="telefono" id="edit_telefono" required class="w-full px-4 py-2 bg-gray-50 border rounded-lg focus:border-orange outline-hidden font-bold">
          </div>
          <div class="mb-4">
            <label class="block text-xs font-black text-gray-400 uppercase mb-1">Correo</label>
            <input type="email" name="correo" id="edit_correo" class="w-full px-4 py-2 bg-gray-50 border rounded-lg focus:border-orange outline-hidden font-bold">
          </div>
          <div class="mb-4">
            <label class="block text-xs font-black text-gray-400 uppercase mb-1">Dirección</label>
            <textarea name="direccion" id="edit_direccion" rows="2" class="w-full px-4 py-2 bg-gray-50 border rounded-lg focus:border-orange outline-hidden font-black"></textarea>
          </div>
          <div class="flex items-center gap-2 mb-4">
            <input type="checkbox" name="estado" id="edit_estado" class="w-4 h-4 accent-orange">
            <label class="text-sm font-bold text-navy-dark uppercase">Activo</label>
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
  <div id="modalDetalle" class="fixed inset-0 z-160 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
      <div class="fixed inset-0 bg-navy-dark/80 backdrop-blur-md" onclick="closeDetalleModal()"></div>
      <div class="relative bg-white shadow-2xl rounded-[14px] w-full max-w-md animate-fade-up overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50/50 flex justify-between items-center">
          <h3 class="text-xl font-black text-navy-dark">Detalle del Cliente</h3>
          <button type="button" onclick="closeDetalleModal()" class="text-gray-400 hover:text-navy-dark"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div class="p-6 space-y-4">
          <div><p class="text-[0.65rem] font-black text-gray-400 uppercase mb-1">Cédula</p><p id="det_cedula" class="font-black text-navy-dark">—</p></div>
          <div><p class="text-[0.65rem] font-black text-gray-400 uppercase mb-1">Nombre Completo</p><p id="det_nombre" class="font-bold text-navy-light uppercase">—</p></div>
          <div><p class="text-[0.65rem] font-black text-gray-400 uppercase mb-1">Teléfono</p><p id="det_telefono" class="font-semibold text-gray-700">—</p></div>
          <div><p class="text-[0.65rem] font-black text-gray-400 uppercase mb-1">Correo</p><p id="det_correo" class="font-semibold text-gray-500">—</p></div>
          <div><p class="text-[0.65rem] font-black text-gray-400 uppercase mb-1">Dirección</p><p id="det_direccion" class="font-semibold text-gray-600 text-sm">—</p></div>
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
    const modal = document.getElementById('modalCliente');
    const btnOpen = document.getElementById('btnOpenModal');
    const toggleModal = () => modal.classList.toggle('hidden');
    btnOpen.onclick = toggleModal;

    let currentRecord = null;

    function viewDetails(data) {
      currentRecord = data;
      document.getElementById('det_cedula').textContent = data.cedula_cliente;
      document.getElementById('det_nombre').textContent = data.nombre;
      document.getElementById('det_telefono').textContent = data.telefono || '—';
      document.getElementById('det_correo').textContent = data.correo || '—';
      document.getElementById('det_direccion').textContent = data.direccion || '—';
      document.getElementById('det_estado').textContent = data.estado == 1 ? 'Activo' : 'Inactivo';
      document.getElementById('btnDetalleEditar').onclick = () => { closeDetalleModal(); openEditModal(currentRecord); };
      document.getElementById('btnDetalleEliminar').onclick = () => confirmDelete(currentRecord.cedula_cliente);
      document.getElementById('modalDetalle').classList.remove('hidden');
    }

    function closeDetalleModal() {
      document.getElementById('modalDetalle').classList.add('hidden');
    }

    function openEditModal(data) {
        document.getElementById('edit_id_actual').value = data.cedula_cliente;
        document.getElementById('edit_cedula').value = data.cedula_cliente;
        document.getElementById('edit_nombre').value = data.nombre;
        document.getElementById('edit_telefono').value = data.telefono;
        document.getElementById('edit_correo').value = data.correo;
        document.getElementById('edit_direccion').value = data.direccion;
        document.getElementById('edit_estado').checked = data.estado == 1;
        document.getElementById('modalEditCliente').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modalEditCliente').classList.add('hidden');
    }

    function confirmDelete(id) {
      if(confirm('¿Desea desactivar este cliente?')) {
        const f = new FormData();
        f.append('deleteCliente', 'true');
        f.append('idcliente', id);
        fetch('?url=cliente&type=main', { method:'POST', body:f })
        .then(r => r.json()).then(d => {
          if(d.status === 'success') location.reload();
          else alert('No se pudo eliminar el cliente.');
        });
      }
    }
  </script>
</body>
</html>
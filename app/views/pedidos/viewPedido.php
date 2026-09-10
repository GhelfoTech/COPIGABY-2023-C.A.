<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CopiGaby — Gestión de Pedidos</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Pacifico&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/dist.css">
  <link rel="stylesheet" href="assets/css/theme.css">
  <link rel="stylesheet" href="assets/css/dashboard.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="font-['Nunito'] bg-[#f0f2f7] text-[#1f2937] min-h-screen flex">

  <?php include 'app/views/layouts/viewMenuLateral.php'; ?>

  <div class="ml-[260px] flex-1 flex flex-col min-h-screen">
    <header class="h-16 bg-white border-b-2 border-orange flex items-center justify-between px-8 sticky top-0 z-50 shadow-[0_2px_12px_rgba(0,0,0,0.06)]">
      <h2 class="text-navy-dark font-extrabold text-xl uppercase tracking-tighter">Gestión de Pedidos</h2>
      <div class="flex items-center">
        <?php include 'app/views/layouts/viewUsuarioHeader.php'; ?>
      </div>
    </header>

    <main class="p-8 flex-1 animate-fade-up">
      <?php if (!empty($pedidoFlash)): ?>
      <div id="pedidoFlash" class="mb-6 px-5 py-4 rounded-xl border font-bold text-sm <?= ($pedidoFlash['status'] ?? '') === 'success' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700' ?>">
        <?= htmlspecialchars($pedidoFlash['message'] ?? 'Operación completada.') ?>
      </div>
      <?php endif; ?>

      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-2xl font-[900] text-gray-800">Módulo de Pedidos</h1>
          <p class="text-gray-500 text-sm font-semibold">Registro de pedidos de productos y servicios</p>
          <?php if (!empty($monedaActiva)): ?>
          <p class="text-xs font-bold text-orange-dk mt-2">
            Moneda: <?= htmlspecialchars($monedaActiva['nombre_moneda']) ?> (<?= htmlspecialchars($monedaActiva['simbolo']) ?>)
            — Tasa: <?= number_format($tasaActual, 2) ?> Bs
          </p>
          <?php endif; ?>
        </div>
        <button id="btnOpenModal" class="flex items-center gap-2 bg-gradient-to-r from-orange to-orange-dk text-navy-dark font-black px-6 py-3 rounded-xl shadow-lg hover:-translate-y-1 transition-all">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
          AÑADIR PEDIDO
        </button>
      </div>

      <div class="bg-white rounded-custom shadow-sm overflow-hidden border border-gray-100">
        <table class="w-full text-left">
          <thead class="bg-navy-dark text-white text-[0.7rem] uppercase tracking-widest">
            <tr>
              <th class="px-6 py-4 text-center">ID</th>
              <th class="px-6 py-4">Cliente</th>
              <th class="px-6 py-4">Atendido por</th>
              <th class="px-6 py-4">Fecha</th>
              <th class="px-6 py-4 text-right">Monto Total</th>
              <th class="px-6 py-4">Método Pago</th>
              <th class="px-6 py-4 text-center">Estado</th>
              <th class="px-6 py-4 text-center">Consultar</th>
            </tr>
          </thead>
          <tbody class="text-gray-700 font-semibold text-sm divide-y">
            <?php if (!empty($pedidos)): ?>
            <?php foreach ($pedidos as $ped): ?>
              <tr class="hover:bg-gray-50/80 transition-colors">
                <td class="px-6 py-4 text-center text-gray-400 font-bold">#<?= str_pad($ped['codigo_pedido'], 4, '0', STR_PAD_LEFT) ?></td>
                <td class="px-6 py-4 uppercase text-[0.8rem] font-bold text-navy-light"><?= htmlspecialchars($ped['nombre_cliente'] ?? '—') ?></td>
                <td class="px-6 py-4 text-gray-500"><?= htmlspecialchars($ped['nombre_usuario'] ?? '—') ?></td>
                <td class="px-6 py-4 text-gray-500"><?= date('d/m/Y H:i', strtotime($ped['fecha_pedido'])) ?></td>
                <td class="px-6 py-4 text-right">
                  <?php $tasaPed = (float) ($ped['tasa_cambio'] ?? $tasaActual); ?>
                  <div class="font-black text-navy-dark text-[1rem]"><?= htmlspecialchars($monedaActiva['simbolo'] ?? '$') ?><?= number_format((float) ($ped['monto_total'] ?? 0), 2) ?></div>
                  <div class="text-[0.65rem] text-orange-dk font-bold"><?= number_format((float) ($ped['monto_total'] ?? 0) * $tasaPed, 2) ?> Bs</div>
                </td>
                <td class="px-6 py-4 text-xs font-bold text-gray-500 uppercase"><?= htmlspecialchars($ped['nombre_metodo'] ?? '—') ?></td>
                <td class="px-6 py-4 text-center">
                  <span class="px-3 py-1 rounded-full text-[0.65rem] font-black uppercase <?= $ped['estado'] ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' ?>">
                    <?= $ped['estado'] ? 'Activo' : 'Anulado' ?>
                  </span>
                </td>
                <td class="px-6 py-4 text-center">
                  <button type="button" onclick="viewDetails(<?= $ped['codigo_pedido'] ?>)" class="group relative text-orange-dk p-2 hover:bg-orange/10 rounded-lg transition-colors" title="Ver Detalle">
                    <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block bg-navy-dark text-white text-[10px] px-2 py-1 rounded whitespace-nowrap pointer-events-none z-10 shadow-lg">Consultar</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="8" class="px-6 py-10 text-center text-gray-400 font-bold italic">No se encontraron pedidos registrados.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <!-- Modal Crear / Editar Pedido -->
  <div id="modalPedido" class="fixed inset-0 z-[150] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
      <div class="fixed inset-0 bg-navy-dark/60 backdrop-blur-sm" id="overlayPedido"></div>
      <div class="relative bg-white shadow-xl rounded-custom w-full max-w-7xl animate-fade-up overflow-hidden">
        <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50/50">
          <h3 id="modalPedidoTitulo" class="text-xl font-black text-navy-dark tracking-tight">Nuevo Pedido</h3>
          <button type="button" class="text-gray-400 hover:text-navy-dark closeModalPedido">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
        <form id="formPedido" action="?url=pedido&type=register" method="POST" class="p-6 text-left">
          <input type="hidden" name="items" id="itemsJson">
          <input type="hidden" name="codigo_pedido" id="codigoPedidoEdit" value="">
          <input type="hidden" name="ajax" value="1">
          <input type="hidden" name="subtotal" id="inputSubtotal" value="0.00">
          <input type="hidden" name="monto_iva" id="inputMontoIva" value="0.00">
          <input type="hidden" name="porcentaje_iva" id="inputPorcentajeIva" value="0.00">
          <input type="hidden" name="monto_total" id="inputMontoTotal" value="0.00">
          <input type="hidden" name="tasa_actual" id="inputTasaActual" value="<?= number_format($tasaActual, 2, '.', '') ?>">

           <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
             <div>
               <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Cliente <span class="text-red-500">*</span></label>
               <select name="codigo_cliente" id="selectCliente" required class="w-full px-4 py-2 bg-gray-50 border rounded-lg focus:ring-2 focus:ring-orange/20 focus:border-orange outline-none font-bold">
                <option value="">— Seleccione un cliente —</option>
                <?php foreach ($clientes as $cli): ?>
                  <option value="<?= $cli['cedula_cliente'] ?>"><?= htmlspecialchars($cli['nombre']) ?> (<?= htmlspecialchars($cli['cedula_cliente']) ?>)</option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Moneda / Tasa Global</label>
              <div class="w-full px-4 py-2 bg-orange/10 border border-orange/30 rounded-lg font-bold text-navy-dark">
                <span id="monedaActivaLabel"><?= htmlspecialchars($monedaActiva['nombre_moneda'] ?? '—') ?></span>
                <span class="text-orange-dk ml-1">(<?= htmlspecialchars($monedaActiva['simbolo'] ?? '$') ?>)</span>
                <span class="block text-xs text-gray-500 mt-1 font-semibold">
                  Tasa: <span id="tasaActualLabel"><?= number_format($tasaActual, 2) ?></span> Bs 
                </span>
              </div>
              <input type="hidden" id="tasaActualInput" value="<?= number_format($tasaActual, 2, '.', '') ?>">
            </div>
          </div>

          <div class="mb-4 flex justify-between items-center">
            <h4 class="text-sm font-black text-navy-dark uppercase tracking-wider">Detalle del Pedido</h4>
            <button type="button" id="btnAddItem" class="flex items-center gap-1 text-xs font-black uppercase text-orange hover:text-orange-dk transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
              Agregar ítem
            </button>
          </div>

           <div class="overflow-x-auto border rounded-lg mb-4">
             <table class="w-full text-left text-sm">
                 <thead>
                  <tr class="bg-navy-dark text-white text-xs uppercase tracking-widest">
                    <th class="px-2 py-2 w-20">Tipo</th>
                    <th class="px-2 py-2 w-80">Descripción</th>
                     <th class="px-2 py-2 w-20">Unidad</th>
                     <th class="px-2 py-2 w-16">Cant</th>
                     <th class="px-2 py-2 w-20">Cant. Unid</th>
                     <th class="px-2 py-2 w-20">Cant. Total</th>
                    <th class="px-2 py-2 w-20">Precio</th>
                    <th class="px-2 py-2 w-24 text-right">Subtotal</th>
                    <th class="px-2 py-2 w-10"></th>
                  </tr>
                </thead>
               <tbody id="itemsBody" class="divide-y font-semibold text-gray-700">
                 <tr id="emptyRow">
                   <td colspan="9" class="px-4 py-6 text-center text-gray-400 italic">Agregue al menos un producto o servicio</td>
                 </tr>
               </tbody>
            </table>
          </div>

          <div class="flex justify-end mb-6">
            <div class="bg-gray-50 border rounded-xl px-6 py-4 text-right min-w-[280px]">
              <div class="flex justify-between items-center text-sm mb-2">
                <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Subtotal</span>
                <span id="subtotalGeneral" class="font-bold text-navy-dark">$0.00</span>
              </div>
              <div class="flex justify-between items-center text-sm mb-3 pb-3 border-b border-gray-200 gap-3">
                <div class="flex items-center gap-2">
                  <span class="text-xs font-black text-gray-400 uppercase tracking-widest whitespace-nowrap">IVA</span>
                  <select name="codigo_IVA" id="selectIva" required class="px-2 py-1 bg-white border rounded-lg text-xs font-bold focus:border-orange outline-none min-w-[110px]">
                    <?php if (!empty($ivas)): ?>
                      <?php foreach ($ivas as $iva): ?>
                        <option value="<?= (int) $iva['codigo_IVA'] ?>"
                          data-porcentaje="<?= number_format((float) $iva['porcentaje_iva'], 2, '.', '') ?>"
                          <?= ((int) $iva['codigo_IVA'] === (int) ($ivaActivo['codigo_IVA'] ?? 0)) ? 'selected' : '' ?>>
                          <?= number_format((float) $iva['porcentaje_iva'], 2) ?>%
                        </option>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <option value="<?= (int) ($ivaActivo['codigo_IVA'] ?? 1) ?>"
                        data-porcentaje="<?= number_format((float) ($ivaActivo['porcentaje_iva'] ?? 16), 2, '.', '') ?>">
                        <?= number_format((float) ($ivaActivo['porcentaje_iva'] ?? 16), 2) ?>%
                      </option>
                    <?php endif; ?>
                  </select>
                </div>
                <span id="montoIva" class="font-bold text-orange-dk">$0.00</span>
              </div>
              <span class="text-xs font-black text-gray-400 uppercase tracking-widest mr-4">Total General</span>
              <span id="totalGeneral" class="text-2xl font-black text-navy-dark">$0.00</span>
              <div id="totalBs" class="text-xs font-bold text-orange-dk mt-1">0.00 Bs</div>
            </div>
          </div>

          <!-- Sección de Pago -->
          <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-4 mb-6">
            <h4 class="text-sm font-black text-navy-dark uppercase tracking-wider mb-4">Datos del Pago</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Método de Pago</label>
                <select name="codigo_metodo" id="selectMetodo" required class="w-full px-4 py-2 bg-white border rounded-lg focus:border-orange outline-none font-bold">
                  <option value="">— Seleccione —</option>
                  <?php foreach ($metodos as $m): ?>
                    <option value="<?= $m['codigo_metodo'] ?>"><?= htmlspecialchars($m['nombre_metodo']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div id="wrapBanco" class="hidden">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Banco</label>
                <select name="codigo_banco" id="selectBanco" class="w-full px-4 py-2 bg-white border rounded-lg focus:border-orange outline-none font-bold">
                  <option value="">— Seleccione banco —</option>
                  <?php foreach ($bancos as $b): ?>
                    <option value="<?= $b['codigo_banco'] ?>"><?= htmlspecialchars($b['nombre_banco']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div id="wrapReferencia" class="hidden">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Referencia / Comprobante</label>
                <input type="text" name="referencia_pago" id="referenciaPago" maxlength="50" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full px-4 py-2 bg-white border rounded-lg focus:border-orange outline-none font-bold" placeholder="N° de referencia (solo números)">
              </div>
            </div>
          </div>

          <div class="flex justify-end gap-3">
            <button type="button" class="px-6 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-lg closeModalPedido">Cancelar</button>
            <button type="submit" id="btnSubmitPedido" class="px-8 py-2 text-sm font-black bg-navy-dark text-white rounded-lg hover:bg-navy transition-all">Registrar Pedido</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Ver Detalle (solo lectura) -->
  <div id="modalDetalle" class="fixed inset-0 z-[160] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
      <div class="fixed inset-0 bg-navy-dark/80 backdrop-blur-md" onclick="closeDetalleModal()"></div>
      <div class="relative bg-white shadow-2xl rounded-custom w-full max-w-3xl animate-fade-up overflow-hidden">
        <div class="p-8">
          <div class="flex justify-between items-start border-b pb-6 mb-6">
            <div>
              <h3 class="text-2xl font-black text-navy-dark uppercase tracking-tighter" id="det_codigo">PEDIDO #0000</h3>
              <p class="text-gray-400 font-bold text-sm" id="det_fecha">Fecha: --/--/----</p>
            </div>
            <div class="text-right">
              <p class="text-xs font-black text-orange uppercase tracking-widest">Cliente</p>
              <h4 class="font-black text-navy-light uppercase" id="det_cliente">—</h4>
              <p class="text-gray-500 text-xs font-bold" id="det_telefono">Tel: —</p>
            </div>
          </div>

          <div class="mb-6">
            <table class="w-full text-left" id="tablaDetallesItems">
                 <thead class="bg-gray-50 text-gray-400 text-[0.65rem] uppercase font-black tracking-widest">
                   <tr>
                     <th class="px-4 py-3 text-navy-dark">Descripción</th>
                     <th class="px-4 py-3 text-center">Tipo</th>
                     <th class="px-4 py-3 text-center">Unidad</th>
                     <th class="px-4 py-3 text-center">Cant.</th>
                     <th class="px-4 py-3 text-right">Precio</th>
                     <th class="px-4 py-3 text-right">Subtotal</th>
                   </tr>
                 </thead>
              <tbody class="divide-y text-sm font-bold text-navy-dark"></tbody>
            </table>
          </div>

          <div class="grid grid-cols-2 gap-4 mb-6 bg-gray-50 rounded-xl p-4 text-sm">
            <div>
              <p class="text-[0.65rem] font-black text-gray-400 uppercase mb-1">Método de Pago</p>
              <p class="font-bold text-navy-dark" id="det_metodo">—</p>
            </div>
            <div>
              <p class="text-[0.65rem] font-black text-gray-400 uppercase mb-1">Total Pagado</p>
              <p class="font-bold text-navy-dark" id="det_monto_pago">—</p>
            </div>
            <div>
              <p class="text-[0.65rem] font-black text-gray-400 uppercase mb-1">IVA Aplicado</p>
              <p class="font-bold text-orange-dk" id="det_iva">—</p>
            </div>
            <div id="det_banco_wrap" class="hidden">
              <p class="text-[0.65rem] font-black text-gray-400 uppercase mb-1">Banco</p>
              <p class="font-bold text-navy-dark" id="det_banco">—</p>
            </div>
            <div id="det_ref_wrap" class="hidden">
              <p class="text-[0.65rem] font-black text-gray-400 uppercase mb-1">Referencia</p>
              <p class="font-bold text-navy-dark" id="det_referencia">—</p>
            </div>
          </div>

          <div class="flex justify-between items-end border-t pt-6">
            <div>
              <p class="text-[0.65rem] font-black text-gray-400 uppercase mb-1">Atendido por</p>
              <p class="text-navy-dark font-bold text-sm" id="det_usuario">—</p>
            </div>
            <div class="text-right">
              <p class="text-xs text-gray-400 font-bold mb-1">Subtotal: <span id="det_subtotal">$0.00</span></p>
              <span class="text-xs font-black text-gray-400 uppercase mr-4">Total del Pedido</span>
              <span class="text-3xl font-black text-navy-dark" id="det_total">$0.00</span>
              <div class="text-sm font-bold text-orange-dk mt-1" id="det_total_bs">0.00 Bs</div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-gray-50 px-8 py-4 flex justify-end gap-3 border-t">
          <button type="button" id="btnDetalleEliminar" class="px-5 py-2 text-sm font-black text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">Eliminar</button>
          <button type="button" id="btnDetalleEditar" class="px-5 py-2 text-sm font-black text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">Modificar</button>
          <button type="button" onclick="closeDetalleModal()" class="bg-navy-dark text-white font-black px-8 py-2 rounded-lg text-xs uppercase hover:bg-navy transition-all">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const productos = <?= json_encode($productos, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    const servicios = <?= json_encode($servicios, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    const medidas = <?= json_encode($medidas, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    const tasaActual = <?= json_encode($tasaActual) ?>;
    const ivaDefaultCodigo = <?= json_encode((int) ($ivaActivo['codigo_IVA'] ?? 1)) ?>;
    const simboloMoneda = <?= json_encode($monedaActiva['simbolo'] ?? '$') ?>;
    const METODOS_CON_BANCO = [563, 564];

    const modalPedido = document.getElementById('modalPedido');
    const modalDetalle = document.getElementById('modalDetalle');
    const btnOpen = document.getElementById('btnOpenModal');
    const closeBtns = document.querySelectorAll('.closeModalPedido');
    const overlayPedido = document.getElementById('overlayPedido');
    const itemsBody = document.getElementById('itemsBody');
    const totalGeneral = document.getElementById('totalGeneral');
    const subtotalGeneral = document.getElementById('subtotalGeneral');
    const montoIva = document.getElementById('montoIva');
    const totalBs = document.getElementById('totalBs');
    const formPedido = document.getElementById('formPedido');
    const btnSubmitPedido = document.getElementById('btnSubmitPedido');
    const itemsJsonInput = document.getElementById('itemsJson');
    const modalPedidoTitulo = document.getElementById('modalPedidoTitulo');
    const codigoPedidoEdit = document.getElementById('codigoPedidoEdit');
    const selectMetodo = document.getElementById('selectMetodo');
    const wrapBanco = document.getElementById('wrapBanco');
    const wrapReferencia = document.getElementById('wrapReferencia');
    const selectIva = document.getElementById('selectIva');
    const inputSubtotal = document.getElementById('inputSubtotal');
    const inputMontoIva = document.getElementById('inputMontoIva');
    const inputPorcentajeIva = document.getElementById('inputPorcentajeIva');
    const inputMontoTotal = document.getElementById('inputMontoTotal');
    const inputTasaActual = document.getElementById('inputTasaActual');

    let itemCounter = 0;
    let editMode = false;

    function roundMoney(value) {
      const num = parseFloat(value);
      if (isNaN(num)) return 0;
      return Math.round(num * 100) / 100;
    }

    function formatMoney(value) {
      return roundMoney(value).toFixed(2);
    }

    function getTasaFormulario() {
      const tasaInput = document.getElementById('tasaActualInput');
      const tasa = parseFloat(tasaInput ? tasaInput.value : tasaActual);
      return isNaN(tasa) ? parseFloat(tasaActual) : tasa;
    }

    function syncHiddenFinancialFields(subtotal, iva, porcentajeIva, total) {
      if (inputSubtotal) inputSubtotal.value = formatMoney(subtotal);
      if (inputMontoIva) inputMontoIva.value = formatMoney(iva);
      if (inputPorcentajeIva) inputPorcentajeIva.value = formatMoney(porcentajeIva);
      if (inputMontoTotal) inputMontoTotal.value = formatMoney(total);
      if (inputTasaActual) inputTasaActual.value = formatMoney(getTasaFormulario());
    }

    function getPorcentajeIvaSeleccionado() {
      if (!selectIva || selectIva.selectedIndex < 0) return 0;
      const option = selectIva.options[selectIva.selectedIndex];
      return parseFloat(option.getAttribute('data-porcentaje') || '0');
    }

    function computeFinancialTotals() {
      let subtotal = 0;
      itemsBody.querySelectorAll('tr[data-item]').forEach(row => {
        subtotal += parseFloat(row.dataset.subtotal || 0);
      });
      subtotal = roundMoney(subtotal);
      const porcentajeIva = getPorcentajeIvaSeleccionado();
      const iva = roundMoney(subtotal * (porcentajeIva / 100));
      const total = roundMoney(subtotal + iva);
      return { subtotal, porcentajeIva, iva, total };
    }

    function setIvaSeleccionado(codigoIva) {
      if (!selectIva) return;
      const codigo = String(codigoIva || ivaDefaultCodigo);
      if ([...selectIva.options].some(opt => opt.value === codigo)) {
        selectIva.value = codigo;
      } else if (selectIva.options.length > 0) {
        selectIva.selectedIndex = 0;
      }
    }

    function toggleModal(forceClose = false) {
      if (forceClose) {
        modalPedido.classList.add('hidden');
        return;
      }
      modalPedido.classList.toggle('hidden');
    }

    function resetFormPedido() {
      editMode = false;
      codigoPedidoEdit.value = '';
      formPedido.action = '?url=pedido&type=register';
      modalPedidoTitulo.textContent = 'Nuevo Pedido';
      btnSubmitPedido.textContent = 'Registrar Pedido';
      formPedido.reset();
      document.getElementById('tasaActualInput').value = formatMoney(tasaActual);
      if (inputTasaActual) inputTasaActual.value = formatMoney(tasaActual);
      setIvaSeleccionado(ivaDefaultCodigo);
      itemsBody.innerHTML = '<tr id="emptyRow"><td colspan="9" class="px-4 py-6 text-center text-gray-400 italic">Agregue al menos un producto o servicio</td></tr>';
      subtotalGeneral.textContent = simboloMoneda + '0.00';
      montoIva.textContent = simboloMoneda + '0.00';
      totalGeneral.textContent = simboloMoneda + '0.00';
      totalBs.textContent = '0.00 Bs';
      syncHiddenFinancialFields(0, 0, getPorcentajeIvaSeleccionado(), 0);
      toggleCamposBanco();
    }

    btnOpen.onclick = () => {
      resetFormPedido();
      toggleModal();
    };
    closeBtns.forEach(btn => btn.onclick = () => { toggleModal(true); resetFormPedido(); });
    overlayPedido.onclick = () => { toggleModal(true); resetFormPedido(); };

    function toggleCamposBanco() {
      const metodo = parseInt(selectMetodo.value, 10);
      const requiere = METODOS_CON_BANCO.includes(metodo);
      wrapBanco.classList.toggle('hidden', !requiere);
      wrapReferencia.classList.toggle('hidden', !requiere);
      document.getElementById('selectBanco').required = requiere;
      document.getElementById('referenciaPago').required = requiere;
    }

    function buildOptions(tipo) {
      const list = tipo === 'producto' ? productos : servicios;
      let html = '<option value="">— Seleccionar —</option>';
      list.forEach(item => {
        const id = tipo === 'producto' ? item.codigo_producto : item.codigo_servicio;
        const nombre = tipo === 'producto' ? item.nombre_producto : item.nombre_servicio;
        const precio = tipo === 'producto' ? item.precio : item.precio;
        const stock = tipo === 'producto' ? item.stock_actual : '';
        const cantidadUnidad = tipo === 'producto' ? (parseInt(item.cantidad_unidad || '1', 10) || 1) : 1;
        const codigoMedia = tipo === 'producto' ? (item.codigo_media || '') : '';
        const abreviatura = tipo === 'producto' ? (item.abreviatura_medida || '') : '';
        const nombreMedida = tipo === 'producto' ? (item.nombre_medida || '') : '';
        const extra = stock !== '' ? ` data-stock="${stock}"` : '';
        const extraUnidad = tipo === 'producto' ? ` data-cantidad-unidad="${cantidadUnidad}" data-codigo-media="${codigoMedia}" data-abreviatura="${abreviatura}" data-nombre-medida="${nombreMedida}"` : '';
        html += `<option value="${id}" data-precio="${parseFloat(precio)}"${extra}${extraUnidad}>${nombre}</option>`;
      });
      return html;
    }

    function recalcTotal() {
      const { subtotal, porcentajeIva, iva, total } = computeFinancialTotals();
      const tasa = getTasaFormulario();

      subtotalGeneral.textContent = simboloMoneda + formatMoney(subtotal);
      montoIva.textContent = simboloMoneda + formatMoney(iva);
      totalGeneral.textContent = simboloMoneda + formatMoney(total);
      totalBs.textContent = formatMoney(total * tasa) + ' Bs';
      syncHiddenFinancialFields(subtotal, iva, porcentajeIva, total);
    }

    function normalizeCantidad(input) {
      let value = parseInt(input.value, 10);
      if (isNaN(value) || value < 1) value = 1;
      input.value = String(value);
      return value;
    }

    function getCantidadTotal(row) {
      const tipo = row.querySelector('.item-tipo').value;
      const cant = normalizeCantidad(row.querySelector('.item-cant'));
      const cantUnidad = normalizeCantidad(row.querySelector('.item-cant-unidad'));
      if (tipo === 'producto') {
        const selectItem = row.querySelector('.item-select');
        const option = selectItem ? selectItem.options[selectItem.selectedIndex] : null;
        const codigoMedia = option ? (option.getAttribute('data-codigo-media') || '') : '';
        if (!codigoMedia) {
          return cant;
        }
      }
      return cant * cantUnidad;
    }

    function updateRowSubtotal(row) {
      const cantidadTotal = getCantidadTotal(row);
      const precio = parseFloat(row.querySelector('.item-precio').value) || 0;
      const subtotal = roundMoney(cantidadTotal * precio);
      row.querySelector('.item-subtotal').textContent = simboloMoneda + formatMoney(subtotal);
      row.dataset.subtotal = String(subtotal);
      const cantidadTotalInput = row.querySelector('.item-cantidad-total');
      if (cantidadTotalInput) cantidadTotalInput.value = String(cantidadTotal);
      recalcTotal();
    }

    function onTipoChange(row) {
      const tipo = row.querySelector('.item-tipo').value;
      row.querySelector('.item-select').innerHTML = buildOptions(tipo);
      const unidadCell = row.querySelector('.item-unidad-cell');
      if (unidadCell) {
        unidadCell.classList.toggle('hidden', tipo !== 'producto');
      }
      row.querySelector('.item-precio').value = '';
      row.querySelector('.item-cant').value = '1';
      row.querySelector('.item-cant-unidad').value = '1';
      row.querySelector('.item-cantidad-total').value = '1';
      row.querySelector('.item-subtotal').textContent = simboloMoneda + '0.00';
      row.querySelector('.item-unidad-info').textContent = '';
      row.querySelector('.item-stock-info').textContent = '';
      row.dataset.subtotal = '0';
      recalcTotal();
    }

    function onItemSelect(row) {
      const selectItem = row.querySelector('.item-select');
      const option = selectItem ? selectItem.options[selectItem.selectedIndex] : null;
      const precio = option ? option.getAttribute('data-precio') : '';
      const precioInput = row.querySelector('.item-precio');
      if (precioInput) precioInput.value = precio !== null && precio !== '' ? precio : '';

      const unidadSel = row.querySelector('.item-unidad');
      const unidadesUnidadInput = row.querySelector('.item-cant-unidad');
      const unidadInfo = row.querySelector('.item-unidad-info');
      const cantidadInput = row.querySelector('.item-cant');

      if (unidadSel && option) {
        const codigoMedia = option.getAttribute('data-codigo-media');
        const cantidadUnidad = parseInt(option.getAttribute('data-cantidad-unidad') || '1', 10) || 1;
        
        if (codigoMedia) {
          unidadSel.value = codigoMedia;
        } else {
          unidadSel.value = '';
        }
        
        if (unidadesUnidadInput) {
          unidadesUnidadInput.value = String(cantidadUnidad);
        }
        
        if (unidadInfo) {
          const abreviatura = option.getAttribute('data-abreviatura') || '';
          const nombreMedida = option.getAttribute('data-nombre-medida') || '';
          const texto = abreviatura ? `${abreviatura} / ${nombreMedida}` : (nombreMedida || '');
          unidadInfo.textContent = codigoMedia ? (texto ? `Unidad: ${texto} (${cantidadUnidad} und)` : '') : '';
        }

        if (cantidadInput) {
          const cantidad = parseInt(cantidadInput.value, 10);
          if (!isNaN(cantidad) && cantidad >= 1) {
            const cantidadTotal = codigoMedia ? (cantidad * cantidadUnidad) : cantidad;
            const cantidadTotalInput = row.querySelector('.item-cantidad-total');
            if (cantidadTotalInput) cantidadTotalInput.value = String(cantidadTotal);
          }
        }

        const stockInfo = row.querySelector('.item-stock-info');
        if (stockInfo) {
          const stock = option.getAttribute('data-stock');
          stockInfo.textContent = stock !== null ? `Stock disponible: ${stock} und` : '';
        }
      }

      updateRowSubtotal(row);
    }

    function removeItemRow(tr) {
      tr.remove();
      if (!itemsBody.querySelector('tr[data-item]')) {
        itemsBody.innerHTML = '<tr id="emptyRow"><td colspan="9" class="px-4 py-6 text-center text-gray-400 italic">Agregue al menos un producto o servicio</td></tr>';
        subtotalGeneral.textContent = simboloMoneda + '0.00';
        montoIva.textContent = simboloMoneda + '0.00';
        totalGeneral.textContent = simboloMoneda + '0.00';
        totalBs.textContent = '0.00 Bs';
      } else {
        recalcTotal();
      }
    }

    function bindItemRow(tr) {
      tr.querySelector('.item-tipo').addEventListener('change', () => onTipoChange(tr));
      tr.querySelector('.item-select').addEventListener('change', () => onItemSelect(tr));
      tr.querySelector('.item-cant').addEventListener('input', () => updateRowSubtotal(tr));
      tr.querySelector('.item-cant').addEventListener('change', () => updateRowSubtotal(tr));
      tr.querySelector('.item-cant-unidad').addEventListener('input', () => updateRowSubtotal(tr));
      tr.querySelector('.item-cant-unidad').addEventListener('change', () => updateRowSubtotal(tr));
      tr.querySelector('.item-unidad').addEventListener('change', () => {
        const unidadSel = tr.querySelector('.item-unidad');
        const unidadesUnidadInput = tr.querySelector('.item-cant-unidad');
        if (unidadSel && unidadesUnidadInput) {
          const selectedOption = unidadSel.options[unidadSel.selectedIndex];
          const cantidadUnidad = selectedOption ? (parseInt(selectedOption.getAttribute('data-cantidad-unidad') || '1', 10) || 1) : 1;
          unidadesUnidadInput.value = String(cantidadUnidad);
        }
        updateRowSubtotal(tr);
      });
      tr.querySelector('.btn-remove').addEventListener('click', () => removeItemRow(tr));
    }

    function populateUnidadSelect(row) {
      const unidadSel = row.querySelector('.item-unidad');
      if (!unidadSel) return;
      let html = '<option value="">— Sin unidad —</option>';
      medidas.forEach(m => {
        html += `<option value="${m.codigo_media}" data-cantidad-unidad="${m.cantidad_unidad || 1}">${m.abreviatura || m.nombre} — ${m.nombre}</option>`;
      });
      unidadSel.innerHTML = html;
    }

    function addItemRow(prefill = null) {
      console.log('addItemRow called', prefill);
      const emptyRow = document.getElementById('emptyRow');
      if (emptyRow) emptyRow.remove();

      itemCounter += 1;
      const tr = document.createElement('tr');
      tr.id = 'item-' + itemCounter;
      tr.dataset.item = '1';
      tr.dataset.subtotal = '0';
      tr.innerHTML = `
        <td class="px-2 py-2">
          <select class="item-tipo w-full px-2 py-1.5 bg-gray-50 border rounded-lg text-xs font-bold focus:border-orange outline-none">
            <option value="producto">Producto</option>
            <option value="servicio">Servicio</option>
          </select>
        </td>
         <td class="px-2 py-2">
           <select class="item-select w-full px-2 py-1.5 bg-gray-50 border rounded-lg text-xs font-bold focus:border-orange outline-none" required></select>
           <div class="item-unidad-info text-[0.65rem] text-gray-500 font-semibold mt-1"></div>
           <div class="item-stock-info text-[0.65rem] text-orange font-semibold mt-0.5"></div>
         </td>
        <td class="px-2 py-2 item-unidad-cell">
          <select class="item-unidad w-full px-2 py-1.5 bg-gray-50 border rounded-lg text-xs font-bold focus:border-orange outline-none">
            <option value="">— Seleccionar unidad —</option>
          </select>
        </td>
        <td class="px-2 py-2">
          <input type="number" step="1" min="1" value="1" class="item-cant w-full px-2 py-1.5 bg-white border rounded-lg text-xs font-bold focus:border-orange outline-none">
        </td>
         <td class="px-2 py-2">
           <input type="number" step="1" min="1" value="1" class="item-cant-unidad w-full px-2 py-1.5 bg-slate-800 text-slate-400 cursor-not-allowed border rounded-lg text-xs font-bold outline-none" readonly tabindex="-1">
         </td>
         <td class="px-2 py-2">
           <input type="number" step="1" min="1" value="1" readonly tabindex="-1" class="item-cantidad-total w-full px-2 py-1.5 bg-slate-800 text-slate-400 cursor-not-allowed border rounded-lg text-xs font-bold outline-none">
         </td>
        <td class="px-2 py-2">
          <input type="number" step="0.01" min="0" readonly tabindex="-1" class="item-precio w-full px-2 py-1.5 bg-slate-800 text-slate-400 cursor-not-allowed border rounded-lg text-xs font-bold outline-none">
        </td>
        <td class="px-2 py-2 text-right font-black text-navy-dark item-subtotal">${simboloMoneda}0.00</td>
        <td class="px-2 py-2 text-center">
          <button type="button" class="btn-remove text-red-400 hover:text-red-600 p-1" title="Quitar">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </td>
      `;

      itemsBody.appendChild(tr);
      bindItemRow(tr);
      populateUnidadSelect(tr);

      if (prefill) {
        tr.querySelector('.item-tipo').value = prefill.tipo;
        tr.querySelector('.item-select').innerHTML = buildOptions(prefill.tipo);
         const selectVal = prefill.tipo === 'producto' ? prefill.codigo_producto : prefill.codigo_servicio;
         tr.querySelector('.item-select').value = String(selectVal);
         const unidadCell = tr.querySelector('.item-unidad-cell');
         if (unidadCell) unidadCell.classList.toggle('hidden', prefill.tipo !== 'producto');
         const unidadSel = tr.querySelector('.item-unidad');
         if (unidadSel && prefill.codigo_unidad_medida) unidadSel.value = String(prefill.codigo_unidad_medida);

         const selectOption = tr.querySelector('.item-select').options[tr.querySelector('.item-select').selectedIndex];
         const cantidadUnidadPrefill = selectOption ? (parseInt(selectOption.getAttribute('data-cantidad-unidad') || '1', 10) || 1) : 1;
         const inputUnidades = tr.querySelector('.item-cant-unidad');
         if (inputUnidades) inputUnidades.value = String(cantidadUnidadPrefill);

         const cantidadTotalPrefill = parseInt(prefill.cantidad, 10) || 1;
         const unidadesPrefill = Math.max(1, Math.round(cantidadTotalPrefill / cantidadUnidadPrefill));
          tr.querySelector('.item-cant').value = String(unidadesPrefill);
          if (inputUnidades) inputUnidades.value = String(cantidadUnidadPrefill);
          tr.querySelector('.item-cantidad-total').value = String(cantidadTotalPrefill);

        tr.querySelector('.item-precio').value = formatMoney(prefill.precio_venta);
        updateRowSubtotal(tr);
     } else {
         tr.querySelector('.item-select').innerHTML = buildOptions('producto');
     }
    }

    function collectItemsFromRows() {
      const rows = itemsBody.querySelectorAll('tr[data-item]');
      if (rows.length === 0) {
        return { ok: false, message: 'Debe agregar al menos un ítem al pedido.', items: [] };
      }

      const items = [];
      for (const row of rows) {
        const tipo = row.querySelector('.item-tipo').value;
        const selectItem = row.querySelector('.item-select');
        const id = selectItem.value;
        const cantidadTotal = getCantidadTotal(row);
        const precio = parseFloat(row.querySelector('.item-precio').value);

        if (!id) return { ok: false, message: 'Seleccione producto o servicio en cada línea.', items: [] };
        if (isNaN(precio) || precio < 0) return { ok: false, message: 'Precio no válido en una de las líneas.', items: [] };

        const item = { tipo, cantidad: cantidadTotal, precio_venta: roundMoney(precio) };
        if (tipo === 'producto') {
          item.codigo_producto = parseInt(id, 10);
          const option = selectItem.options[selectItem.selectedIndex];
          const stockAttr = option.getAttribute('data-stock');
          const stock = stockAttr !== null ? parseInt(stockAttr, 10) : null;
          if (stock !== null && cantidadTotal > stock) {
            return { ok: false, message: 'Stock insuficiente para: ' + option.text, items: [] };
          }
          const unidadSelect = row.querySelector('.item-unidad');
          if (unidadSelect) {
            item.codigo_unidad_medida = parseInt(unidadSelect.value, 10) || null;
          }
        } else {
          item.codigo_servicio = parseInt(id, 10);
        }
        items.push(item);
      }
      return { ok: true, message: '', items };
    }

    async function submitPedido(e) {
      e.preventDefault();

      if (!document.getElementById('selectCliente').value) {
        Swal.fire({ icon: 'warning', title: 'Cliente requerido', text: 'Seleccione un cliente.' });
        return;
      }

      const collected = collectItemsFromRows();
      if (!collected.ok) {
        Swal.fire({ icon: 'warning', title: 'Detalle incompleto', text: collected.message });
        return;
      }

      recalcTotal();
      const totals = computeFinancialTotals();

      const formData = new FormData(formPedido);
      formData.set('items', JSON.stringify(collected.items));
      formData.set('codigo_IVA', selectIva ? selectIva.value : String(ivaDefaultCodigo));
      formData.set('subtotal', formatMoney(totals.subtotal));
      formData.set('monto_iva', formatMoney(totals.iva));
      formData.set('porcentaje_iva', formatMoney(totals.porcentajeIva));
      formData.set('monto_total', formatMoney(totals.total));
      formData.set('tasa_actual', formatMoney(getTasaFormulario()));
      formData.set('codigo_metodo', selectMetodo.value);
      if (selectMetodo.value && METODOS_CON_BANCO.includes(parseInt(selectMetodo.value, 10))) {
        formData.set('codigo_banco', document.getElementById('selectBanco').value);
        formData.set('referencia_pago', document.getElementById('referenciaPago').value.trim());
      }
      itemsJsonInput.value = JSON.stringify(collected.items);

      const originalLabel = btnSubmitPedido.textContent;
      btnSubmitPedido.disabled = true;
      btnSubmitPedido.textContent = 'Guardando...';

      const url = editMode ? '?url=pedido&type=update' : '?url=pedido&type=register';

      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          body: formData,
        });
        const data = await response.json();

        if (data.status === 'success') {
          Swal.fire({ icon: 'success', title: 'Éxito', text: data.message || 'Operación completada.', timer: 1500, showConfirmButton: false })
            .then(() => location.reload());
          return;
        }
        Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'No se pudo procesar el pedido.' });
      } catch (err) {
        Swal.fire({ icon: 'error', title: 'Error de comunicación', text: 'Revise la consola o el log de PHP.' });
      } finally {
        btnSubmitPedido.disabled = false;
        btnSubmitPedido.textContent = originalLabel;
      }
    }

    let detailPedidoId = null;

    function viewDetails(id) {
      fetch(`?url=pedido&type=details&id=${id}`)
        .then(r => r.json())
        .then(data => {
          const h = data.header;
          const items = data.items;
          if (!h) return;

          detailPedidoId = h.codigo_pedido;

          document.getElementById('det_codigo').innerText = 'PEDIDO #' + String(h.codigo_pedido).padStart(4, '0');
          document.getElementById('det_fecha').innerText = 'Fecha: ' + h.fecha_pedido;
          document.getElementById('det_cliente').innerText = h.nombre_cliente || '—';
          document.getElementById('det_telefono').innerText = 'Tel: ' + (h.telefono_cliente || '—');
          document.getElementById('det_usuario').innerText = h.nombre_usuario || '—';

          const subtotalDet = parseFloat(h.subtotal ?? 0);
          const totalDet = parseFloat(h.monto_total ?? 0);
          const ivaDet = parseFloat(h.monto_iva ?? 0);
          const pctIvaDet = parseFloat(h.porcentaje_iva ?? 0);

          document.getElementById('det_subtotal').innerText = simboloMoneda + formatMoney(subtotalDet);
          document.getElementById('det_total').innerText = simboloMoneda + formatMoney(totalDet);
          const tasaDet = parseFloat(h.tasa_actual ?? h.tasa_cambio ?? tasaActual) || parseFloat(tasaActual);
          document.getElementById('det_total_bs').innerText = formatMoney(totalDet * tasaDet) + ' Bs';
          document.getElementById('det_iva').innerText = simboloMoneda + formatMoney(ivaDet) + ' (' + formatMoney(pctIvaDet) + '%)';
          document.getElementById('det_metodo').innerText = h.nombre_metodo || '—';

          const pago = h.pago || null;
          document.getElementById('det_monto_pago').innerText = pago
            ? simboloMoneda + formatMoney(parseFloat(pago.monto))
            : simboloMoneda + formatMoney(totalDet);

          const bancoWrap = document.getElementById('det_banco_wrap');
          const refWrap = document.getElementById('det_ref_wrap');
          if (pago && pago.nombre_banco) {
            bancoWrap.classList.remove('hidden');
            document.getElementById('det_banco').innerText = pago.nombre_banco;
          } else {
            bancoWrap.classList.add('hidden');
          }
          const refPago = pago ? (pago.numero_comprobante || pago.referencia || '') : '';
          if (refPago) {
            refWrap.classList.remove('hidden');
            document.getElementById('det_referencia').innerText = refPago;
          } else {
            refWrap.classList.add('hidden');
          }

          const tbody = document.querySelector('#tablaDetallesItems tbody');
          tbody.innerHTML = '';
           items.forEach(it => {
             const nombre = it.tipo === 'servicio' ? it.nombre_servicio : it.nombre_producto;
             const unidadDisplay = it.tipo === 'producto' ? (it.abreviatura_medida || it.nombre_medida || '—') : '—';
             tbody.innerHTML += `
               <tr>
                 <td class="px-4 py-3 uppercase">${nombre}</td>
                 <td class="px-4 py-3 text-center text-xs uppercase text-gray-500">${it.tipo}</td>
                 <td class="px-4 py-3 text-center font-bold text-navy-light">${unidadDisplay}</td>
                 <td class="px-4 py-3 text-center">${parseFloat(it.cantidad)}</td>
                 <td class="px-4 py-3 text-right">${simboloMoneda}${formatMoney(parseFloat(it.precio_venta))}</td>
                 <td class="px-4 py-3 text-right font-black text-orange-dk">${simboloMoneda}${formatMoney(parseFloat(it.subtotal))}</td>
               </tr>`;
           });

          const activo = parseInt(h.estado, 10) === 1;
          document.getElementById('btnDetalleEditar').classList.toggle('hidden', !activo);
          document.getElementById('btnDetalleEliminar').classList.toggle('hidden', !activo);
          document.getElementById('btnDetalleEditar').onclick = () => {
            closeDetalleModal();
            openEditPedido(detailPedidoId);
          };
          document.getElementById('btnDetalleEliminar').onclick = () => confirmDelete(detailPedidoId);

          modalDetalle.classList.remove('hidden');
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar el detalle del pedido.' }));
    }

    function closeDetalleModal() {
      modalDetalle.classList.add('hidden');
    }

    function openEditPedido(id) {
      fetch(`?url=pedido&type=details&id=${id}`)
        .then(r => r.json())
        .then(data => {
          const h = data.header;
          const items = data.items;
          if (!h) return;

          resetFormPedido();
          editMode = true;
          codigoPedidoEdit.value = h.codigo_pedido;
          formPedido.action = '?url=pedido&type=update';
          modalPedidoTitulo.textContent = 'Modificar Pedido #' + String(h.codigo_pedido).padStart(4, '0');
          btnSubmitPedido.textContent = 'Actualizar Pedido';

          document.getElementById('selectCliente').value = String(h.cedula_cliente);
          setIvaSeleccionado(h.codigo_IVA || ivaDefaultCodigo);

          const tasaPed = parseFloat(h.tasa_actual ?? h.tasa_cambio ?? tasaActual);
          document.getElementById('tasaActualInput').value = formatMoney(tasaPed);
          if (inputTasaActual) inputTasaActual.value = formatMoney(tasaPed);
          document.getElementById('tasaActualLabel').textContent = formatMoney(tasaPed);

           items.forEach(it => {
             addItemRow({
               tipo: it.tipo,
               codigo_producto: it.codigo_producto,
               codigo_servicio: it.codigo_servicio,
               cantidad: it.cantidad,
               precio_venta: it.precio_venta,
               codigo_media: it.codigo_media,
             });
           });

          if (h.codigo_metodo) {
            selectMetodo.value = String(h.codigo_metodo);
          }
          toggleCamposBanco();

          const pago = h.pago || null;
          if (pago && pago.codigo_banco) {
            document.getElementById('selectBanco').value = String(pago.codigo_banco);
          }
          const refEdit = pago ? (pago.numero_comprobante || pago.referencia || '') : '';
          if (refEdit) {
            document.getElementById('referenciaPago').value = refEdit;
          }

          const subtotalH = parseFloat(h.subtotal ?? 0);
          const ivaH = parseFloat(h.monto_iva ?? 0);
          const pctH = parseFloat(h.porcentaje_iva ?? getPorcentajeIvaSeleccionado());
          const totalH = parseFloat(h.monto_total ?? 0);

          subtotalGeneral.textContent = simboloMoneda + formatMoney(subtotalH);
          montoIva.textContent = simboloMoneda + formatMoney(ivaH);
          totalGeneral.textContent = simboloMoneda + formatMoney(totalH);
          totalBs.textContent = formatMoney(totalH * tasaPed) + ' Bs';
          syncHiddenFinancialFields(subtotalH, ivaH, pctH, totalH);

          modalPedido.classList.remove('hidden');
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar el pedido para edición.' }));
    }

    function confirmDelete(id) {
      Swal.fire({
        title: '¿Anular pedido?',
        text: 'Se revertirá el inventario y el pedido quedará inactivo. Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar',
      }).then(result => {
        if (!result.isConfirmed) return;
        const f = new FormData();
        f.append('deletePedido', 'true');
        f.append('idpedido', String(id));
        fetch('?url=pedido&type=main', { method: 'POST', body: f })
          .then(r => r.json())
          .then(d => {
            if (d.status === 'success') {
              Swal.fire({ icon: 'success', title: 'Anulado', text: d.message || 'Pedido anulado.', timer: 1500, showConfirmButton: false })
                .then(() => location.reload());
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: d.message || 'No se pudo anular el pedido.' });
            }
          })
          .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Error de comunicación al anular el pedido.' }));
      });
    }

    selectMetodo.addEventListener('change', toggleCamposBanco);
    if (selectIva) {
      selectIva.addEventListener('change', recalcTotal);
    }

    document.getElementById('btnAddItem').addEventListener('click', () => addItemRow());
    formPedido.addEventListener('submit', submitPedido);
  </script>
</body>
</html>
